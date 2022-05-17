<?php
#Feggu/Core/Partner/Models/FegguPartnerCss.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguPartnerCss extends Model
{
    protected $primaryKey = 'partner_id';
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'feggu_partner_css';
    protected $connection = AU_CONNECTION;
}
