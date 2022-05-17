<?php
#Feggu/Core/Partner/Models/FegguCustomFieldDetail.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class FegguCustomFieldDetail extends Model
{
    public $timestamps     = false;
    public $table          = AU_DB_PREFIX.'feggu_custom_field_detail';
    protected $connection  = AU_CONNECTION;
    protected $guarded     = [];

    //Function get text description
    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(
            function ($obj) {
                //
            }
        );
    }
}
