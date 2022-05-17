<?php
#Feggu/Core/Partner/Models/FegguLinkStore.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguLinkPartner extends Model
{
    protected $primaryKey = ['partner_id', 'link_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'feggu_link_partner';
    protected $connection = AU_CONNECTION;
}
