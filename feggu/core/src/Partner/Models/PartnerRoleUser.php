<?php
#Feggu/Core/Front/Models/FegguPatient.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PartnerRoleUser extends Model
{
    public $table = AU_DB_PREFIX . 'partner_role_user';
    public $timestamps = false;
    protected $guarded=[];
    protected $primaryKey = ['role_id', 'user_id'];
    public $incrementing  = false;

    use UsesTenantConnection;
}
