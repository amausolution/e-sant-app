<?php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class FegguRegion extends Model
{
    public $table = AU_DB_PREFIX.'feggu_region';
    public $timestamps               = false;
    private static $getListCountries = null;
    private static $getCodeAll = null;
    protected $connection = AU_CONNECTION;

    public static function getListAll()
    {
        if (self::$getListCountries === null) {
            self::$getListCountries = self::get()->keyBy('code');
        }
        return self::$getListCountries;
    }

    public static function getCodeAll()
    {
        self::$getCodeAll = self::pluck('name', 'code')->all();
        return self::$getCodeAll;
    }
}
