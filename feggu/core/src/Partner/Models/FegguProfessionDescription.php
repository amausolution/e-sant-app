<?php


namespace Feggu\Core\Partner\Models;


use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class FegguProfessionDescription extends Model
{
    use UsesTenantConnection;
    protected $primaryKey = ['lang', 'profession_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'feggu_profession_description';
    protected $connection = AU_CONNECTION;
}
