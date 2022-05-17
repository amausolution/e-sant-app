<?php
#Feggu/Core/Partner/Models/FegguCustomField.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Feggu\Core\Partner\Models\FegguCustomFieldDetail;

class FegguCustomField extends Model
{
    public $timestamps     = false;
    public $table          = AU_DB_PREFIX.'feggu_custom_field';
    protected $connection  = AU_CONNECTION;
    protected $guarded     = [];

    public function details()
    {
        $data  = (new FegguCustomFieldDetail)->where('custom_field_id', $this->id)
            ->get();
        return $data;
    }

    /**
     * Get custom fields
     */
    public function getCustomField($type)
    {
        return $this->where('type', $type)
            ->where('status', 1)
            ->get();
    }

    //Function get text description
    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(
            function ($obj) {
                //
            }
        );
    }
}
