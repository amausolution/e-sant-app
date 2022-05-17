<?php
#Feggu/Core/Partner/Models/FegguBannerPartner.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguBannerPartner extends Model
{
    protected $primaryKey = ['partner_id', 'banner_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'shop_banner_store';
    protected $connection = AU_CONNECTION;
}
