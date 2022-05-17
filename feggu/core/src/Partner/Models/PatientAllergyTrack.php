<?php


namespace Feggu\Core\Partner\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientAllergyTrack extends Model
{
    use ModelTrait;
    use SoftDeletes;
    public $table = AU_DB_PREFIX.'patient_allergy_track';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;

    public function allergy()
    {
        return $this->belongsTo(PatientAllergy::class, 'pathology_id','id');
    }
    public function doctor()
    {
        return $this->belongsTo(FegguDoctor::class, 'doctor_id','id');
    }
    public function hospital()
    {
        return $this->belongsTo(FegguPartner::class, 'hospital_id','id');
    }
}
