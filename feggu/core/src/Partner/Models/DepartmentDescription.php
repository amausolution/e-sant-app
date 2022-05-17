<?php


namespace Feggu\Core\Partner\Models;


use Illuminate\Database\Eloquent\Model;

class DepartmentDescription extends Model
{
    protected $primaryKey = ['lang', 'department_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'department_description';
    protected $connection = AU_CONNECTION;
}
