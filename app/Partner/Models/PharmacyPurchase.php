<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguPartner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyPurchase extends Model
{
    use HasFactory;

    protected $connection = 'pharmacy';
    protected $table = AU_DB_PREFIX . 'purchases';
    protected $guarded =[];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

}
