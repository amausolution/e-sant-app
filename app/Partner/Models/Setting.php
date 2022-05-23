<?php


namespace App\Partner\Models;


use Feggu\Core\Partner\Models\FegguPartner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Setting extends Model
{
    protected $table = AU_DB_PREFIX.'table_setting';
    protected $guarded =[];
    protected $connection = AU_CONNECTION;
    use SoftDeletes, UsesTenantConnection;

    public function partner()
    {
        return $this->belongsTo(FegguPartner::class,'partner_id','id');
    }
}
