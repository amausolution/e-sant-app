<?php

namespace App\Partner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableYear extends Model
{
    use HasFactory;

    protected $table = AU_DB_PREFIX.'timetable_year';
    protected $connection = AU_CONNECTION;
    protected $guarded = [];
    public $timestamps = false;

    public function months()
    {
        return $this->hasMany(TimetableMonth::class,'year_id','id');
    }
}
