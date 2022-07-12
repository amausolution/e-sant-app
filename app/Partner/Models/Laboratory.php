<?php

namespace App\Partner\Models;

use App\Models\ConfigDefault;
use Feggu\Core\Partner\Models\FegguAnalyseDetail;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\ModelTrait;
use Feggu\Core\Partner\Models\PartnerConfig;
use Feggu\Core\Partner\Models\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Laboratory extends Model
{
    protected $table = AU_DB_PREFIX.'laboratory';
    protected $guarded =[];
    protected $connection = 'laboratory';
    use SoftDeletes, ModelTrait, UuidTrait ;


    public function partner()
    {
        return $this->belongsTo(FegguPartner::class,'partner','id');
    }
    public function typeAnalyses()
    {
        return $this->hasMany(LabTypeAnalyse::class, 'laboratory_id','id');
    }
    public function analyses()
    {
        return $this->hasMany(FegguAnalyseDetail::class, 'labo_id','id');
    }



    public static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = au_generate_id();
            }
        });
        static::deleting(
            function ($model) {
                //Delete custom field
            }
        );
    }
}
