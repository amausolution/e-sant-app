<?php
#Feggu/Core/Partner/Models/FegguPaymentPartner.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguPaymentPartner extends Model
{
    protected $primaryKey = ['partner_id', 'payment_status_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'feggu_payment_partner';
    protected $connection = AU_CONNECTION;
}
