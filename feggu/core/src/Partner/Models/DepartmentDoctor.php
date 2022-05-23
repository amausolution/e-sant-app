<?php


namespace Feggu\Core\Partner\Models;


use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class DepartmentDoctor extends Model
{
    protected $primaryKey = ['doctor_id', 'department_partner_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'doctor_department';
    protected $connection = AU_CONNECTION;

    use UsesTenantConnection;


}
