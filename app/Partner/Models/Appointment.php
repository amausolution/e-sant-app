<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\FegguUser;
use Feggu\Core\Partner\Models\PartnerUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    public $table = AU_DB_PREFIX . 'appointment_patient';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;

    public function user()
    {
          return $this->belongsTo(PartnerUser::class,'user_id','id');
    }

    public function partner()
    {
        return $this->belongsTo(FegguPartner::class,'partner_id','id');
    }
    public function patient()
    {
        return $this->belongsTo(FegguUser::class,'patient_id','id');
    }

}
