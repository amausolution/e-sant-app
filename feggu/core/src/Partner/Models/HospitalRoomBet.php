<?php
#Feggu/Core/Front/Models/PartnerConsultation.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class HospitalRoomBet extends Model
{
    protected $guarded    = [];
    public $table = AU_DB_PREFIX.'hospital_room_bed';
    protected $connection = AU_CONNECTION;
    use UsesTenantConnection;

    public function room()
    {
        return $this->belongsTo(HospitalRoom::class,'room_id','id');
    }



}
