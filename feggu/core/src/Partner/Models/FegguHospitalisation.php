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
        $query->when($filters['first_name'] ?? null, function ($query, $first_name) {
            $query->where(function ($query) use ($first_name) {
                $query->whereHas('patient', function ($query) use ($first_name) {
                    $query->where('first_name', 'like', '%'.$first_name.'%');
                });
            });
        })->when($filters['last_name'] ?? null, function ($query, $last_name) {
            $query->where(function ($query) use ($last_name) {
                $query->whereHas('patient', function ($query) use ($last_name) {
                    $query->where('last_name', 'like', '%'.$last_name.'%');
                });
            });
        })->when($filters['accompanying'] ?? null, function ($query, $accompanying) {
            $query->where(function ($query) use ($accompanying) {
                $query->where('accompanying', 'like', '%'.$accompanying.'%');
            });
        })->when($filters['identifier'] ?? null, function ($query, $identifier) {
            $query->where(function ($query) use ($identifier) {
                $query->whereHas('patient', function ($query) use ($identifier) {
                    $query->where('doc_number', '=', $identifier)
                    ->orWhere('mobil', '=', $identifier)
                    ->orWhere('number_piece', '=', $identifier);
                });
            });
        });
    }

}
