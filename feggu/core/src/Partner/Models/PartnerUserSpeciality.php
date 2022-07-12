<?php


namespace Feggu\Core\Partner\Models;


use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PartnerUserSpeciality extends Model
{
    protected $primaryKey = ['doctor_id', 'speciality_id'];
    public $incrementing  = false;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'doctor_speciality';

    use UsesTenantConnection;
}
