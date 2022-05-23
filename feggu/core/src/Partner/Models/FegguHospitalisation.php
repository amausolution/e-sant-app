<?php
#Feggu/Core/Front/Models/PartnerConsultation.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FegguHospitalisation extends Model
{
    protected $guarded    = [];
    public $table = AU_DB_PREFIX.'hospital_hospitalisation';
    protected $connection = 'consultation';

    use SoftDeletes, UuidTrait;


    public function hospital()
    {
        return $this->belongsTo(FegguPartner::class,'hospital_id','id');
    }
    public function patient()
    {
        return $this->belongsTo(FegguUser::class, 'patient_id', 'id');
    }

    public function room()
    {
       return $this->belongsTo(HospitalRoom::class,'room_id','id');
    }
    public function bed()
    {
        return $this->belongsTo(HospitalRoomBet::class,'bed_id','id');
    }
    public function doctor()
    {
        return $this->belongsTo(PartnerUser::class, 'doctor_id', 'id');
    }
    public function hospitalized()
    {
        return $this->belongsTo(PartnerUser::class, 'hospitalized_by', 'id');
    }

    public function consultations()
    {
        return $this->belongsToMany(FegguConsultation::class, HospitalisationConsultation::class,'hosp_id','consultation_id');
    }

    public function consultation()
    {
      return $this->belongsTo(FegguConsultation::class,'consultation_id','id') ;
    }


    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = au_generate_id();
            }
        });
        static::deleting(function ($model) {
            $model->deleted_by = \Partner::user()->id;
            $model->deleted_by_ip = request()->ip();
            $model->deleted_by_user_agent = request()->server('HTTP_USER_AGENT');
        });
    }



    public function scopeOrderByName($query)
    {
        $query->orderBy('created_at','asc');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%');
            });
        })->when($filters['patientId'] ?? null, function ($query, $identifier) {
            $query->where(function ($query) use ($identifier) {
                $query->where('doc_number', '=', $identifier);
            });
        })->when($filters['gender'] ?? null, function ($query, $gender) {
            $query->where(function ($query) use ($gender) {
                $query->where('gender', '=', $gender);
            });
        });
    }

}
