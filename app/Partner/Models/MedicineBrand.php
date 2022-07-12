<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguPartner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineBrand extends Model
{
    use HasFactory;

    protected $connection = 'pharmacy';
    protected $table = AU_DB_PREFIX . 'brands';
    protected $guarded =[];

    public function products()
    {
        return $this->hasMany(PharmacyProduct::class,'brand_id','id');
    }

}
