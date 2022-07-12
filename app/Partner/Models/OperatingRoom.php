<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\FegguPartner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class OperatingRoom extends Model
{
    use HasFactory,SoftDeletes,UsesTenantConnection;
    protected $table = AU_DB_PREFIX.'operating_room';
    protected $guarded = [];

    public function hospital()
    {
        return $this->belongsTo(FegguPartner::class,'partner_id','id');
    }
}
