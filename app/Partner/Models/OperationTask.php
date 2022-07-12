<?php

namespace App\Partner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperationTask extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = AU_DB_PREFIX.'operation_task';
    protected $guarded = [];
    protected $connection = 'consultation';

    public function operation()
    {
        return $this->belongsTo(Operation::class,'operation_id','id');
    }
}
