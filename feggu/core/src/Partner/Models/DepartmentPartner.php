<?php


namespace Feggu\Core\Partner\Models;


use Illuminate\Database\Eloquent\Model;

class DepartmentPartner extends Model
{
   /* protected $primaryKey = ['partner_id', 'department_id'];
    public $incrementing  = false;*/
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'partner_department';
    protected $connection = AU_CONNECTION;



    public function doctors()
    {
        return $this->belongsToMany(PartnerUser::class,DepartmentDoctor::class,'department_partner_id','doctor_id');
    }











}
