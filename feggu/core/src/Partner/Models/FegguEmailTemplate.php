<?php
#Feggu/Core/Partner/Models/FegguEmailTemplate.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguEmailTemplate extends Model
{
    public $timestamps = false;
    public $table = AU_DB_PREFIX.'feggu_email_template';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;
}
