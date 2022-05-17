<?php
#Feggu/Core/Partner/Models/FegguCustomFieldDetail.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;


class FegguAnalyseDetail extends Model
{
    public $timestamps     = false;
    public $table          = AU_DB_PREFIX.'consultation_analyse_detail';
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
