<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\FegguUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BloodBank extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = AU_DB_PREFIX.'blood_banks';
    protected $connection = AU_CONNECTION;
    protected $guarded = [];

    public function partner()
    {
        return $this->belongsTo(FegguPartner::class,'partner_id','id');
    }
    public function patient()
    {
        return $this->belongsTo(FegguUser::class,'patient_id','id');
    }
}
