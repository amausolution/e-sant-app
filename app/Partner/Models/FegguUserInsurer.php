<?php


namespace App\Partner\Models;


use Feggu\Core\Partner\Models\FegguUser;
use Illuminate\Database\Eloquent\Model;

class FegguUserInsurer extends Model
{
  protected $connection='consultation';
  protected $guarded = [];
  protected $table = AU_DB_PREFIX.'patient_insurer';

    public function patient()
    {
        return $this->belongsTo(FegguUser::class,'patient_id','id');
    }
}
