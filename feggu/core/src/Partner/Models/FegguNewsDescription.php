<?php
#Feggu/Core/Partner/Models/FegguNewsDescription.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguNewsDescription extends Model
{
    protected $primaryKey = ['lang', 'news_id'];
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;
    public $table = AU_DB_PREFIX.'feggu_news_description';
    protected $connection = AU_CONNECTION;
}
