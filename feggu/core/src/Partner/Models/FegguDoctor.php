<?php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\ModelTrait;

class FegguDoctor extends Model
{
    use ModelTrait;

    public $table = AU_DB_PREFIX.'partner_user';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;

    protected $au_type = 'all'; // all or interger


    public function partners()
    {
        return $this->belongsToMany(FegguPartner::class, FegguDoctorPartner::class, 'doctor_id', 'partner_id');
    }

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(function ($banner) {
           // $banner->partner()->detach();
        });
    }


    /**
     * Start new process get data
     *
     * @return  new model
     */
    public function start()
    {
        return new FegguDoctor();
    }

    /*
   Full name
    */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
