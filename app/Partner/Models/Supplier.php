<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguPartner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $connection = 'pharmacy';
    protected $table = AU_DB_PREFIX . 'suppliers';
    protected $guarded =[];

    public function purchases()
    {
        return $this->hasMany(PharmacyPurchase::class,'supplier_id','id');
    }


}
