<?php

namespace App\Partner\Models;

use Feggu\Core\Partner\Models\DepartmentPartner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentRoom extends Model
{
    use HasFactory;
    protected $guarded    = [];
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'department_room';
    protected $connection = AU_CONNECTION;

    public function department()
    {
        return $this->belongsTo(DepartmentPartner::class,'department_partner_id','department_id');
    }
}
