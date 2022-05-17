<?php
#Feggu/Core/Partner/Models/FegguNewsPartner.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguNewsPartner extends Model
{
    protected $primaryKey = ['partner_id', 'news_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'feggu_news_partner';
    protected $connection = AU_CONNECTION;
}
