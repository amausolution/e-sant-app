<?php

namespace Feggu\Core\Partner\Models;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


/**
 * @method static where(string $string, \Illuminate\Contracts\Foundation\Application|\Illuminate\Session\SessionManager|\Illuminate\Session\Store $partner)
 */
class FegguConsultation extends Model //implements HasMedia
{
    use Notifiable, SoftDeletes, UuidTrait;// InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = AU_DB_PREFIX.'consultation';
    protected $guarded = [];
    protected $connection = 'consultation';

    protected $casts = [
        'first_diag'=>'array',
        'diagnostic'=>'array'
    ];



    public function hospital()
    {
        return $this->belongsTo(FegguPartner::class,'hospital_id','id');
    }

    public function doctor()
    {
        return $this->belongsTo(PartnerUser::class,'doctor_id', 'id');
    }
    public function patient()
    {
        return $this->belongsTo(FegguUser::class, 'patient_id','id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id','id');
    }
    public function detail()
    {
        return $this->hasOne(FegguConsultationDetail::class, 'consultation_id','id');
    }
    public function payment()
    {
        return $this->hasOne(FegguConsultationPayment::class, 'consultation_id','id');
    }

    public function prescriptions()
    {
       return $this->hasMany(FegguConsultationPrescription::class,'consultation_id','id');
    }
    public function analyses()
    {
       return $this->hasMany(FegguConsultationAnalyse::class,'consultation_id','id');
    }
    public function hospitalisations()
    {
        return $this->belongsToMany(FegguHospitalisation::class,HospitalisationConsultation::class,'consultation_id','hosp_id');
    }

    public function hospitalisation()
    {
        return $this->hasOne(FegguHospitalisation::class,'consultation_id','id');
    }


    public static function getPatientPartner($id, $doctorId = null)
    {
        $data = self::where('id', $id);
        if ($doctorId) {
            $tableDoctorPatient = (new FegguDoctorPatient)->getTable();
            $tablePatient = (new FegguConsultation)->getTable();
            $data = $data->leftJoin($tableDoctorPatient, $tableDoctorPatient . '.patient_id', $tablePatient . '.id');
            $data = $data->where($tableDoctorPatient . '.doctor_id', $doctorId);
        }
        $data = $data->first();
        return $data;
    }

    public static function getPatientListPartner($partnerId = null)
    {
        $patientList = (new FegguConsultation);
        $tablePatient = $patientList->getTable();
        if ($partnerId) {
            $tablePatientPartner = (new FegguPatient)->getTable();
            $patientList = $patientList->leftJoin($tablePatientPartner, $tablePatientPartner . '.patient_id', $tablePatient . '.id');
            $patientList = $patientList->where($tablePatientPartner . '.partner_id', $partnerId);
        }
        $patientList = $patientList->orderBy($tablePatient.'.id', 'desc');
        $patientList = $patientList->paginate(20);

        return $patientList;
    }

    /*
    Full name
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


    public static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = au_generate_id();
            }
        });
        static::deleting(
            function ($model) {
            //Delete custom field
                $model->deleted_by = \Partner::user()->id;
                $model->deleted_by_ip = request()->ip();
                $model->deleted_by_user_agent = request()->server('HTTP_USER_AGENT');
            }
        );
    }


    /**
     * Update info customer
     * @param  [array] $dataUpdate
     * @param  [int] $id
     */
    public static function updateInfo($dataUpdate, $id)
    {
        $dataUpdate = au_clean($dataUpdate);
        $obj = self::find($id);
        return $obj->update($dataUpdate);
    }

    /**
     * Create new customer
     * @return [type] [description]
     */
    public static function createCustomer($dataInsert)
    {
        $dataClean = au_clean($dataInsert);
        $dataAddress = [
            'first_name'      => $dataClean['first_name'] ?? '',
            'last_name'       => $dataClean['last_name'] ?? '',
            'first_name_kana' => $dataClean['first_name_kana'] ?? '',
            'last_name_kana'  => $dataClean['last_name_kana'] ?? '',
            'postcode'        => $dataClean['postcode'] ?? '',
            'address1'        => $dataClean['address1'] ?? '',
            'address2'        => $dataClean['address2'] ?? '',
            'address3'        => $dataClean['address3'] ?? '',
            'country'         => $dataClean['country'] ?? '',
            'phone'           => $dataClean['phone'] ?? '',
        ];
        $user = self::create($dataClean);
        $address = $user->addresses()->save(new FegguPatientAddress($dataAddress));
        $user->address_id = $address->id;
        $user->save();

        // Process event patient created
        au_event_customer_created($user);

        return $user;
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('address', 'like', '%'.$search.'%')
                    ->orWhereHas('patient', function ($query) use ($search) {
                        $query->where('doc_number', '=', $search)
                        ->orWhere('last_name', 'like', '%'.$search.'%')
                            ->orWhere('first_name', 'like', '%'.$search.'%');
                    });
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
