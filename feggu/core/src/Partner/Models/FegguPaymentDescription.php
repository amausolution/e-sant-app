<?php
#Feggu/Core/Partner/Models/FegguPaymentPartner.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguPaymentDescription extends Model
{
    public $timestamps  = false;
    public $incrementing = false;
    public $table = AU_DB_PREFIX.'feggu_payment_status_detail';
    protected $guarded   = [];
    protected $connection = AU_CONNECTION;
    protected $primaryKey = ['lang', 'payment_status_id'];

}
