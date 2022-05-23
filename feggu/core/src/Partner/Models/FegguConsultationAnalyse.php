<?php
#Feggu/Core/Partner/Models/FegguCustomFieldDetail.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Illuminate\Database\Eloquent\SoftDeletes;

class FegguConsultationAnalyse extends Model
{
    public $table          = AU_DB_PREFIX.'consultation_analyse';
    protected $connection  = 'consultation';
    protected $guarded     = [];

    use SoftDeletes;

    protected $casts = [
        'analyse'=>'array'
    ];

    public function resultat()
    {
       return $this->hasOne(FegguAnalyseDetail::class,'analyse_id','id') ;
    }

    public function consultation()
    {
        return $this->belongsTo(FegguConsultation::class,'consultation_id','id');
    }
    public function doctor()
    {
        return $this->belongsTo(PartnerUser::class,'doctor_id','id');
    }
    public function analysis()
    {
        return $this->belongsTo(CategoryAnalysisDetail::class,'type_id','id');
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
