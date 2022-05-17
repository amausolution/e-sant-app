<?php
#Feggu/Core/Partner/Models/FegguSubscribe.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguSubscribe extends Model
{
    public $table = AU_DB_PREFIX.'subscribe';
    protected $guarded      = [];
    protected $connection = AU_CONNECTION;
}
