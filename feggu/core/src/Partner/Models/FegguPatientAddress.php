<?php
#Feggu/Core/Partner/Models/FegguPatientAddress.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguPatientAddress extends Model
{
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'feggu_customer_address';
    protected $connection = AU_CONNECTION;
}
