<?php

namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerLab extends Model
{
    use HasFactory;

    public $table = AU_DB_PREFIX . 'laboratory';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;

    public function partner()
    {
       return $this->belongsTo(FegguPartner::class,'partner_id' ,'id')  ;
    }

    public function categories()
    {
        return $this->hasMany(LabCategoryAnalysis::class,'lab_id','id');
    }
}
