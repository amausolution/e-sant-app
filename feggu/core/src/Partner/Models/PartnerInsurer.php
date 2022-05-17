<?php
#Feggu/Core/Partner/Models/FegguPatientAddress.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerInsurer extends Model
{
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'hospital_assurance';
    protected $connection = AU_CONNECTION;
}
