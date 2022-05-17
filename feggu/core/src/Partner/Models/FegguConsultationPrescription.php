<?php
#Feggu/Core/Partner/Models/FegguCustomFieldDetail.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class FegguConsultationPrescription extends Model
{

    public $table          = AU_DB_PREFIX.'consultation_prescription';
    protected $connection  = AU_CONNECTION;
    protected $guarded     = [];
    protected $casts = [
        'dosage_text' => 'array',
    ];


    //Function get text description
    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(
            function ($obj) {
                //
            }
        );
    }
}
