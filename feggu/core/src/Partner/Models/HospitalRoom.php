<?php
#Feggu/Core/Front/Models/PartnerConsultation.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class HospitalRoom extends Model
{
    protected $guarded    = [];
    public $table = AU_DB_PREFIX.'hospital_room';
    protected $connection = AU_CONNECTION;

    use SoftDeletes, UsesTenantConnection;

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
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('room_number', '=', $search)
                    ->orWhere('name', 'like', '%'.$search.'%');
            });
        })->when($filters['status'] ?? null, function ($query, $identifier) {
            $query->where(function ($query) use ($identifier) {
                $query->where('status', '=', $identifier);
            });
        });
    }
}
