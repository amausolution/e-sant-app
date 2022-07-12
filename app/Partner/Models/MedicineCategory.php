<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguPartner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MedicineCategory extends Model
{
    use HasFactory;

    protected $connection = 'pharmacy';
    protected $table = AU_DB_PREFIX . 'categories';
    protected $guarded =[];

    public function prescriptions()
    {
        return $this->hasMany(FegguConsultation::class,'pharmacy','id');
    }

    public function products()
    {
        return $this->belongsToMany(PharmacyProduct::class, DB::connection('pharmacy')->table(AU_DB_PREFIX.'category_product'),'product_id','category_id');
    }

}
