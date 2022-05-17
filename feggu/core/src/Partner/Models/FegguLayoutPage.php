<?php
#Feggu/Core/Partner/Models/FegguLayoutPage.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguLayoutPage extends Model
{
    public $timestamps = false;
    public $table = AU_DB_PREFIX.'feggu_layout_page';
    protected $connection = AU_CONNECTION;

    public static function getPages()
    {
        return self::pluck('name', 'key')->all();
    }
}
