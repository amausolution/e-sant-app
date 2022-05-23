<?php
#Feggu/Core/Front/Models/FegguPatient.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class HospitalisationConsultation extends Model
{
    public $table = AU_DB_PREFIX . 'hospitalisation_consultation';
    public $timestamps = false;
    protected $guarded=[];
    protected $primaryKey = ['hosp_id', 'consultation_id'];
    public $incrementing  = false;
    protected $connection = 'consultation';
    public $keyType = 'string';


}
