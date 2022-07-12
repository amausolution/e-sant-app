<?php


namespace Feggu\Core\Partner\Models;


use App\Partner\Models\Laboratory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FegguAnalyseResult extends Model
{
    public $table          = AU_DB_PREFIX.'analyse_resultat';
    protected $connection  = 'consultation';
    protected $guarded     = [];

    use SoftDeletes;

    public function analyse()
    {
        return $this->belongsTo(FegguConsultationAnalyse::class,'analyse_id','id');
    }

    public function labo()
    {
        return $this->belongsTo(Laboratory::class,'labo_id','id');
    }
}
