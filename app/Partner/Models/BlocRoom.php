<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\FegguPartner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class BlocRoom extends Model
{
    use HasFactory,UsesTenantConnection;
    protected $table = AU_DB_PREFIX.'bloc_rooms';
    protected $guarded = [];

    public function partner()
    {
        return $this->belongsTo(FegguPartner::class,'partner_id','id');
    }
}
