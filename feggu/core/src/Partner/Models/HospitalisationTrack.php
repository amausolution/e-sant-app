<?php

namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalisationTrack extends Model
{
    use HasFactory;

    public $table = AU_DB_PREFIX . 'hospitalisation_track';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;

    public function hospitalisation()
    {
        return $this->belongsTo(FegguHospitalisation::class,'hospitalisation_id','id');
    }

}
