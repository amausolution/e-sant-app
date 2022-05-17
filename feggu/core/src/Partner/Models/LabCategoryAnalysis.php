<?php

namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabCategoryAnalysis extends Model
{
    use HasFactory;

    public $table = AU_DB_PREFIX . 'lab_category_analysis';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;

    /*public function categoryAnalysis()
    {
        return $this->hasMany(CategoryAnalysis::class,'category_analysis_id','id');
    }*/
}
