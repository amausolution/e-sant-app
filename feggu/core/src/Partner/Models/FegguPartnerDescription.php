<?php
#Feggu/Core/Partner/Models/FegguPartnerDescription.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguPartnerDescription extends Model
{
    protected $primaryKey = ['lang', 'partner_id'];
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;
    public $table = AU_DB_PREFIX.'admin_partner_description';
    protected $connection = AU_CONNECTION;
}
