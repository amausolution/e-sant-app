<?php
#Feggu/Core/Partner/Models/FegguCustomFieldDetail.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Illuminate\Database\Eloquent\SoftDeletes;

class FegguConsultationPrescription extends Model
{
   use SoftDeletes;
    public $table          = AU_DB_PREFIX.'consultation_prescription';
    protected $connection  = 'consultation';
    protected $guarded     = [];
    protected $casts = [
        'dosage_text' => 'array',
    ];

    public function doctor()
    {
        return $this->belongsTo(PartnerUser::class,'doctor_id','id');
    }
    public function consultation()
    {
        return $this->belongsTo(FegguConsultation::class,'consultation_id','id');
    }


    //Function get text description
    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(
            function ($obj) {
                $obj->doctor_id= \Partner::user()->id;
                $obj->partner_id= session('partnerId');
            }
        );
    }
}
