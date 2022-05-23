<?php
#Feggu/Core/Partner/Models/FegguCustomFieldDetail.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class FegguConsultationDetail extends Model
{
    public $table          = AU_DB_PREFIX.'consultation_detail';
    protected $connection  = AU_CONNECTION;
    protected $guarded     = [];


    public function consultation()
    {
        return $this->belongsTo(FegguConsultation::class,'consultation_id','id');
    }

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
