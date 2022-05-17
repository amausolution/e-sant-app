<?php


namespace Feggu\Core\Partner\Models;


use Illuminate\Database\Eloquent\Model;

class PartnerUserSpeciality extends Model
{
    protected $primaryKey = ['doctor_id', 'speciality_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'feggu_speciality_doctor';
    protected $connection = AU_CONNECTION;
}
