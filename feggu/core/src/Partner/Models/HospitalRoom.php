<?php
#Feggu/Core/Front/Models/PartnerConsultation.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalRoom extends Model
{
    protected $guarded    = [];
    public $table = AU_DB_PREFIX.'hospital_room';
    protected $connection = AU_CONNECTION;

    use SoftDeletes;

    public function hospital()
    {
        return $this->belongsTo(FegguPartner::class,'hospital_id','id');
    }

    public function beds()
    {
        return $this->hasMany(HospitalRoomBet::class,'room_id','id');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($room) { // before delete() method call this
            $room->beds()->delete();
            // do the rest of the cleanup...
        });
    }

}
