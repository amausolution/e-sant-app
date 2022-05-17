<?php
namespace Feggu\Core\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class ApiConnection extends Model
{
    public $timestamps = false;
    public $table = AU_DB_PREFIX.'api_connection';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;
    protected static $getGroup = null;

    public static function check($apiconnection, $apikey)
    {
        return self::where('apikey', $apikey)
                    ->where('apiconnection', $apiconnection)
                    ->where(function ($query) {
                        $query->whereNull('expire')
                              ->orWhere('expire', '>=', date('Y-m-d'));
                    })
                    ->where('status', 1)
                    ->first();
    }
}
