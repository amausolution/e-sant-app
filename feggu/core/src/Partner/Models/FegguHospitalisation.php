<?php
#Feggu/Core/Front/Models/PartnerConsultation.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class FegguHospitalisation extends Model
{
    protected $guarded    = [];
    public $table = AU_DB_PREFIX.'hospital_hospitalisation';
    protected $connection = AU_CONNECTION;

    use SoftDeletes;

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

    public function consultations()
    {
        return $this->belongsToMany(FegguConsultation::class, AU_DB_PREFIX.'hospitalisation_consultation','hospitalisation_id','consultation_id');
    }

    public function consultation()
    {
      return $this->belongsTo(FegguConsultation::class,'consultation_id','id') ;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->deleted_by = \Partner::user()->id;
            $model->deleted_by_ip = request()->ip();
            $model->deleted_by_user_agent = request()->server('HTTP_USER_AGENT');
        });
    }


}
