<?php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\ModelTrait;

class FegguDoctorPartner extends Model
{
    protected $primaryKey = ['partner_id', 'doctor_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'feggu_doctor_partner';
    protected $connection = AU_CONNECTION;

}
