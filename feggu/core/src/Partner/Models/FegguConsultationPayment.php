<?php
#Feggu/Core/Partner/Models/FegguCustomFieldDetail.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;


class FegguConsultationPayment extends Model
{
    public $table          = AU_DB_PREFIX.'consultation_payment';
    protected $connection  = 'consultation';
    protected $guarded     = [];
    public $timestamps = false;

    public function consultation()
    {
       return $this->belongsTo(FegguConsultation::class,'consultation_id','id');
    }


}
