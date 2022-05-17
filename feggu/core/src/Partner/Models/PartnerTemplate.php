<?php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerTemplate extends Model
{
    public $table = AU_DB_PREFIX.'partner_template';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;

    /**
     * Get list template installed
     *
     * @return void
     */
    public function getListTemplate()
    {
        return $this->pluck('name', 'key')
            ->all();
    }


    /**
     * Get list template active
     *
     * @return void
     */
    public function getListTemplateActive()
    {
        $arrTemplate =  $this->where('status', 1)
            ->pluck('name', 'key')
            ->all();
        if (!count($arrTemplate)) {
            $arrTemplate['feggu-light'] = 'Feggu Light';
        }
        return $arrTemplate;
    }
}
