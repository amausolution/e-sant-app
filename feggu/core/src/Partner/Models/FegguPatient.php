<?php
#Feggu/Core/Front/Models/FegguPatient.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FegguPatient extends Model
{
    public $table = AU_DB_PREFIX . 'patient';
    public $timestamps = false;
    protected $guarded=[];
    protected $connection = AU_CONNECTION;
    protected $primaryKey = ['patient_id', 'hospital_id'];
    public $incrementing  = false;
}
