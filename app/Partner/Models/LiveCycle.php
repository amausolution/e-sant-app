<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\FegguPatient;
use Feggu\Core\Partner\Models\PartnerUser;
use Feggu\Core\Partner\Models\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiveCycle extends Model
{
    use HasFactory,SoftDeletes,UuidTrait;
    protected $table = AU_DB_PREFIX.'live_cycles';
    protected $guarded = [];
    protected $connection='consultation';

    public function patient()
    {
        return $this->belongsTo(FegguPatient::class,'patient_id','id');
    }

    public function user()
    {
        return $this->belongsTo(PartnerUser::class,'user_id','id');
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
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('patient', function ($query) use ($search) {
                    $query->where('last_name', 'like', '%'.$search.'%')
                        ->orWhere('first_name', 'like', '%'.$search.'%');
                });
            });
        })->when($filters['gender'] ?? null, function ($query, $gender) {
            $query->where(function ($query) use ($gender) {
                $query->whereHas('patient', function ($query) use ($gender) {
                    $query->where('gender', $gender);
                });
            });
        })->when($filters['identifier'] ?? null, function ($query, $identifier) {
            $query->where(function ($query) use ($identifier) {
                $query->whereHas('patient', function ($query) use ($identifier) {
                    $query->where('doc_number', '=', $identifier)
                        ->orWhere('matricule', '=', $identifier);
                });
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
