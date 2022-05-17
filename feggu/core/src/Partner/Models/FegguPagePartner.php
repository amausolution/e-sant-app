<?php
#Feggu/Core/Partner/Models/FegguPagePartner.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguPagePartner extends Model
{
    protected $primaryKey = ['partner_id', 'page_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'feggu_page_partner';
    protected $connection = AU_CONNECTION;
}
