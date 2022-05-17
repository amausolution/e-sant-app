<?php

namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryAnalysisDetail extends Model
{
    use HasFactory;

    public $table = AU_DB_PREFIX . 'category_analysis_detail';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;

    public function category()
    {
        return $this->belongsTo(CategoryAnalysis::class,'category_analysis_id','id');
    }

}
