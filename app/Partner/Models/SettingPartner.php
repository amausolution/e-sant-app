<?php


namespace App\Partner\Models;


use Feggu\Core\Partner\Models\FegguPartner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SettingPartner extends Model
{
    protected $table = AU_DB_PREFIX.'setting_partners';
    protected $guarded =[];
    protected $connection = AU_CONNECTION;
    use SoftDeletes;

    public function partner()
    {
        return $this->belongsTo(FegguPartner::class,'partner_id','id');
    }
}
