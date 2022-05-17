<?php
#Feggu/Core/Partner/Models/FegguLayoutPosition.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguLayoutPosition extends Model
{
    public $timestamps = false;
    public $table = AU_DB_PREFIX.'feggu_layout_position';
    protected $connection = AU_CONNECTION;

    public static function getPositions()
    {
        return self::pluck('name', 'key')->all();
    }
}
