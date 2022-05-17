<?php
#Feggu/Core/Partner/Models/FegguPartnerDescription.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguSpecialityDescription extends Model
{
    protected $primaryKey = ['lang', 'speciality_id'];
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;
    public $table = AU_DB_PREFIX.'feggu_speciality_description';
    protected $connection = AU_CONNECTION;
}
