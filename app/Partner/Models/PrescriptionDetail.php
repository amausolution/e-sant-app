<?php


namespace App\Partner\Models;


use Feggu\Core\Partner\Models\FegguConsultationPrescription;
use Illuminate\Database\Eloquent\Model;

class PrescriptionDetail extends Model
{
    protected $table = AU_DB_PREFIX.'prescription_payment';
    protected $connection = 'consultation';
    protected $guarded = [];

    public function prescription()
    {
        return $this->belongsTo(FegguConsultationPrescription::class,'prescription_id','id');
    }
}
