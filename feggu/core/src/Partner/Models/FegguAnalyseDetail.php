<?php
#Feggu/Core/Partner/Models/FegguCustomFieldDetail.php
namespace Feggu\Core\Partner\Models;

use App\Partner\Models\Laboratory;
use Illuminate\Database\Eloquent\Model;


class FegguAnalyseDetail extends Model
{
    public $timestamps     = false;
    public $table          = AU_DB_PREFIX.'analyse_resultat';
    protected $connection  = 'consultation';
    protected $guarded     = [];

    public function analyse()
    {
        return $this->belongsTo(FegguConsultationAnalyse::class,'analyse_id','id');
    }
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class,'labo_id','id');
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
