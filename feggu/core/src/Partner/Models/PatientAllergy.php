<?php


namespace Feggu\Core\Partner\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientAllergy extends Model
{
    use ModelTrait;
    use SoftDeletes;
    public $table = AU_DB_PREFIX.'patient_allergy';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;

    public function doctor()
    {
        return $this->belongsTo(FegguDoctor::class,'doctor_id','id');
    }
    public function hospital()
    {
        return $this->belongsTo(FegguPartner::class,'hospital_id','id');
    }
    public function AllergyTrack()
    {
        return $this->hasMany(PatientAllergyTrack::class,'pathology_id','id');
    }
}
