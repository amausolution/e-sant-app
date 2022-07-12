<?php
#Feggu/Core/Partner/Models/FegguCustomFieldDetail.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class FegguConsultationAnalyse extends Model
{
    public $table          = AU_DB_PREFIX.'consultation_analyse';
    protected $connection  = 'consultation';
    protected $guarded     = [];

    use SoftDeletes, UuidTrait;

    protected $casts = [
        'Analyse'=>'array'
    ];

    public function results()
    {
       return $this->hasMany(FegguAnalyseDetail::class,'analyse_id','id') ;
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
        static::creating(function ($model) {
            $model->slug =  strtoupper(au_token(8));
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = au_generate_id();
            }
        });
        static::deleting(
            function ($obj) {
                //
            }
        );
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('slug', $search);
            })->when($filters['matricule'] ?? null, function ($query, $identifier) {
                $query->where(function ($query) use ($identifier) {
                    $query->where('matricule', $identifier)
                        ->orWhere('doc_number', $identifier);
                });
            });
        });
    }
}
