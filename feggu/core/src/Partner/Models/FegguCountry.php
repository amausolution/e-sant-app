<?php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class FegguCountry extends Model
{
    public $table = AU_DB_PREFIX.'feggu_country';
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
        if (au_config_global('cache_status') && au_config_global('cache_country')) {
            if (!Cache::has('cache_country')) {
                if (self::$getCodeAll === null) {
                    self::$getCodeAll = self::pluck('name', 'code')->all();
                }
                au_set_cache('cache_country', self::$getCodeAll);
            }
            return Cache::get('cache_country');
        } else {
            if (self::$getCodeAll === null) {
                self::$getCodeAll = self::pluck('name', 'code')->all();
            }
            return self::$getCodeAll;
        }
    }
}
