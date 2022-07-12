<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguPartner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;

    protected $connection = 'pharmacy';
    protected $table = AU_DB_PREFIX . 'pharmacies';
    protected $guarded =[];

    public function prescriptions()
    {
        return $this->hasMany(FegguConsultation::class,'pharmacy','id');
    }

    public function partner()
    {
        return $this->belongsTo(FegguPartner::class,'partner_id','id');
    }

    public function products()
    {
        return $this->hasMany(PharmacyProduct::class,'pharmacy_id','id');
    }
}
