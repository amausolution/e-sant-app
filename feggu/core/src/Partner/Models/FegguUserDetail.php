<?php

namespace Feggu\Core\Partner\Models;

use Feggu\Core\Partner\Models\FegguEmailTemplate;
use Feggu\Core\Partner\Models\FegguPatientAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Auth;
//use Laravel\Passport\HasApiTokens;
use Feggu\Core\Partner\Models\FegguCustomFieldDetail;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class FegguUserDetail extends Model
{
    use Notifiable;

    protected $table = AU_DB_PREFIX.'patient_detail';
    protected $guarded = [];
    protected $connection = 'patient';
    public $timestamps = false;

    public function patient()
    {
        return $this->belongsTo(FegguUser::class,'patient_id','id');
    }

    public function getRecto()
    {
        return asset($this->piece_recto ?: 'data/avatar/no_file.png');

    }
    public function getVerso()
    {
        return asset($this->piece_verso ?: 'data/avatar/no_file.png');

    }
}
