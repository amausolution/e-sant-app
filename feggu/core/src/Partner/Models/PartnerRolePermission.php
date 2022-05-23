<?php
#Feggu/Core/Front/Models/FegguPatient.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PartnerRolePermission extends Model
{
    public $table = AU_DB_PREFIX . 'partner_role_permission';
    public $timestamps = false;
    protected $guarded=[];
    protected $primaryKey = ['role_id', 'permission_id'];
    public $incrementing  = false;

    use UsesTenantConnection;
}
