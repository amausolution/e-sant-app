<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\CategoryAnalysisDetail;
use Feggu\Core\Partner\Models\DepartmentPartner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabTypeAnalyse extends Model
{
    protected $table = AU_DB_PREFIX.'type_analyse';
    protected $guarded =[];
    protected $connection = 'laboratory';
    use SoftDeletes;

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class,'laboratory_id','id');
    }

    public function category()
    {
        return $this->belongsTo(CategoryAnalysisDetail::class,'analyse','id');
    }
    public function department()
    {
        return $this->belongsTo(DepartmentPartner::class,'department_id','id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('slug', 'like', '%'.$search.'%');
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
