<?php
#Feggu/Core/Partner/Models/FegguBannerType.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguBannerType extends Model
{
    public $timestamps  = false;
    public $table = AU_DB_PREFIX.'feggu_banner_type';
    protected $guarded   = [];
    protected $connection = AU_CONNECTION;
}
