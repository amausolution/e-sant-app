<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\PartnerUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableDoctor extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $table = AU_DB_PREFIX.'timetable_doctor';
    public $connection = AU_CONNECTION;
    public $timestamps = false;

    public function month()
    {
        return $this->belongsTo(TimetableMonth::class, 'month_id','id');
   }

    public function doctor()
    {
        return $this->belongsTo(PartnerUser::class,'doctor_id','id');
   }

    public function partner()
    {
        return $this->belongsTo(FegguPartner::class,'partner_id','id');
   }
}
