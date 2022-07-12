<?php

namespace App\Partner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ambulance extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = AU_DB_PREFIX.'ambulances';
    protected $connection = AU_CONNECTION;
    protected $guarded = [];


}
