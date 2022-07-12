<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PharmacyProduct extends Model
{
    use HasFactory;

    protected $connection = 'pharmacy';
    protected $table = AU_DB_PREFIX . 'products';
    protected $guarded =[];
   use SoftDeletes, UuidTrait;
    public function prescriptions()
    {
        return $this->hasMany(FegguConsultation::class,'pharmacy','id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class,'pharmacy_id','id');
    }
    public function brand()
    {
        return $this->belongsTo(MedicineBrand::class,'brand_id','id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

    public function categories()
    {
        return $this->belongsToMany(MedicineCategory::class, DB::connection('pharmacy')->table(AU_DB_PREFIX.'category_product'),'category_id','product_id');
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

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('patient', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%');
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
