<?php

namespace App\Partner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableMonth extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;
    protected $connection = AU_CONNECTION;
    protected $table = AU_DB_PREFIX.'timetable_month';

    public function year()
    {
        return $this->belongsTo(TimetableYear::class,'year_id','id');
    }

    public function days()
    {
        return $this->hasMany(TimetableDoctor::class,'month_id', 'id');
    }
}
