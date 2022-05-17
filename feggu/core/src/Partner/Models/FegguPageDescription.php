<?php
#Feggu/Core/Partner/Models/FegguPageDescription.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguPageDescription extends Model
{
    protected $primaryKey = ['lang', 'page_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'feggu_page_description';
    protected $connection = AU_CONNECTION;
}
