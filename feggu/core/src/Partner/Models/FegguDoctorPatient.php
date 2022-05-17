<?php


namespace Feggu\Core\Partner\Models;


use Illuminate\Database\Eloquent\Model;

class FegguDoctorPatient extends Model
{
    protected $primaryKey = ['patient_id', 'doctor_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'doctor_patient';
    protected $connection = AU_CONNECTION;
}
