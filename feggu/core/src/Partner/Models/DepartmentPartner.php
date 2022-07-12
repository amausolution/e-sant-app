<?php


namespace Feggu\Core\Partner\Models;


use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class DepartmentPartner extends Model
{
   /* protected $primaryKey = ['partner_id', 'department_id'];
    public $incrementing  = false;*/
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'partner_department';
    protected $connection = AU_CONNECTION;
    use UsesTenantConnection;


    public function doctors()
    {
        return $this->belongsToMany(PartnerUser::class,DepartmentDoctor::class,'department_partner_id','doctor_id');
    }
    public function partner()
    {
        return $this->belongsToMany(FegguPartner::class,DepartmentDoctor::class,'department_partner_id','partner_id');
    }











}
