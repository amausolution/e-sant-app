<?php


namespace Feggu\Core\Partner\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientPathology extends Model
{
    use ModelTrait;
    use SoftDeletes;
    public $table = AU_DB_PREFIX.'patient_pathology';
    protected $guarded = [];
    protected $connection = 'patient';

    public function doctor()
    {
        return $this->belongsTo(FegguDoctor::class,'doctor_id','id');
    }
    public function patient()
    {
        return $this->belongsTo(FegguUser::class,'patient_id','id');
    }
    public function hospital()
    {
        return $this->belongsTo(FegguPartner::class,'hospital_id','id');
    }
    public function pathologyTrack()
    {
        return $this->hasMany(PatientPathologyTrack::class,'pathology_id','id');
    }
}
