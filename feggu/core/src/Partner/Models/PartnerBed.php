<?php


namespace Feggu\Core\Partner\Models;



use Illuminate\Database\Eloquent\Model;

class PartnerBed extends Model
{
    protected $guarded    = [];
    public $table = AU_DB_PREFIX.'hospital_room_bed';
    protected $connection = AU_CONNECTION;

    public function room()
    {
        return $this->belongsTo(HospitalRoom::class,'room_id','id');
    }

}
