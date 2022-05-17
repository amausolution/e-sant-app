<?php

namespace Feggu\Core\Partner\Models;

use Feggu\Core\Partner\Models\FegguEmailTemplate;
use Feggu\Core\Partner\Models\FegguPatientAddress;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Auth;
//use Laravel\Passport\HasApiTokens;
use Feggu\Core\Partner\Models\FegguCustomFieldDetail;
use Illuminate\Auth\AuthenticationException;
use Laravel\Sanctum\HasApiTokens;

class FegguUser extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = AU_DB_PREFIX.'feggu_user';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;
    private static $profile = null;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $appends = [
        'name',
    ];

    public function hospitals()
    {
        return $this->belongsToMany(FegguPartner::class, FegguPatient::class, 'patient_id', 'hospital_id');
    }


    public function doctors()
    {
        return $this->belongsToMany(PartnerUser::class, FegguDoctorPatient::class, 'doctor_id', 'patient_id');
    }

    public function consultations()
    {
     return  $this->hasMany(FegguConsultation::class,'patient_id','id');
    }

    public function pathologies()
    {
        return $this->hasMany(PatientPathology::class,'patient_id','id');
    }
    public function allergies()
    {
        return $this->hasMany(PatientAllergy::class,'patient_id','id');
    }


    public function addresses()
    {
        return $this->hasMany(FegguPatientAddress::class, 'customer_id', 'id');
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
        $patientList = (new FegguConsultation());
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

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($patient){
            $patient->partner_id = session('partnerId');
        });
        // before delete() method call this
        static::deleting(
            function ($customer) {

            //Delete custom field
                (new FegguCustomFieldDetail)
                ->join(AU_DB_PREFIX.'feggu_custom_field', AU_DB_PREFIX.'feggu_custom_field.id', AU_DB_PREFIX.'feggu_custom_field_detail.custom_field_id')
                ->select('code', 'name', 'text')
                ->where(AU_DB_PREFIX.'feggu_custom_field_detail.rel_id', $customer->id)
                ->where(AU_DB_PREFIX.'feggu_custom_field.type', 'customer')
                ->delete();
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
            'address'         => $dataClean['address'] ?? '',
            'detail_address'  => $dataClean['detail_address'] ?? '',
            'locality'        => $dataClean['locality'] ?? '',
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

    public function getAvatar()
    {
      if ($this->gender)  {
         return asset($this->avatar ?: 'data/avatar/user_male.png');
      }

        return asset($this->avatar ?: 'data/avatar/user_female.png');
    }

    public function getLastConsultation()
    {
       return FegguConsultation::with(['prescriptions','analyses','doctor','hospital'])->where('patient_id',$this->id)->where('status',1)->latest()->first() ;
    }



}
