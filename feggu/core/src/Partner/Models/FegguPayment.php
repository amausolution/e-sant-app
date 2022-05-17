<?php
#Feggu/Core/Partner/Models/FegguPaymentPartner.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class FegguPayment extends Model
{
    public $timestamps  = false;
    public $table = AU_DB_PREFIX.'feggu_payment_status';
    protected $guarded   = [];
    protected $connection = AU_CONNECTION;
    protected static $listStatus = null;

    public function partners()
    {
        return $this->belongsToMany(FegguPartner::class, FegguPaymentPartner::class, 'payment_id', 'partner_id');
    }

    public function descriptions()
    {
        return $this->hasMany(FegguPaymentDescription::class, 'payment_status_id', 'id');
    }

    //Function get text description
    public function getText()
    {
        return $this->descriptions()->where('lang', au_get_locale())->first();
    }
    public function getTitle()
    {
        return $this->getText()->name ?? '';
    }
    //End  get text description


    /**
     * Get page detail
     *
     * @param   [string]  $key     [$key description]
     * @param   [string]  $type  [id, alias]
     *
     */
    public function getDetail($key, $type = null)
    {
        if (empty($key)) {
            return null;
        }
        $tableDescription = (new FegguPaymentDescription)->getTable();

        $dataSelect = $this->getTable().'.*, '.$tableDescription.'.*';
        $page = $this->selectRaw($dataSelect)
            ->leftJoin($tableDescription, $tableDescription . '.payment_status_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', au_get_locale());

        $partnerId = config('app.partnerId');
        if (au_config_global('MultiPartnerPro')) {
            $tablePaymentPartner = (new FegguPaymentPartner)->getTable();
            $tablePartner = (new FegguPartner)->getTable();
            $page = $page->join($tablePaymentPartner, $tablePaymentPartner.'.payment_status_id', $this->getTable() . '.id');
            $page = $page->join($tablePartner, $tablePartner . '.id', $tablePaymentPartner.'.partner_id');
            $page = $page->where($tablePaymentPartner.'.partner_id', $partnerId);
        }

        if ($type === null) {
            $page = $page->where($this->getTable() .'.id', (int) $key);
        } else {
            $page = $page->where($type, $key);
        }

        return $page->first();
    }

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(
            function ($page) {
                $page->descriptions()->delete();
                $page->partners()->detach();
            }
        );
    }


    /**
     * Start new process get data
     *
     * @return  new model
     */
    public function start()
    {
        return new FegguPayment;
    }

    /**
     * build Query
     */
    public function buildQuery()
    {
        $tableDescription = (new FegguPaymentDescription)->getTable();

        $dataSelect = $this->getTable().'.*, '.$tableDescription.'.*';
        $query = $this->selectRaw($dataSelect)
            ->leftJoin($tableDescription, $tableDescription . '.payment_status_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', au_get_locale());

        $partnerId = config('app.partnerId');
        if (au_config_global('MultiPartnerPro')) {
            $tablePaymentPartner = (new FegguPaymentPartner)->getTable();
            $tablePartner = (new FegguPartner)->getTable();
            $query = $query->join($tablePaymentPartner, $tablePaymentPartner.'.payment_status_id', $this->getTable() . '.id');
            $query = $query->join($tablePartner, $tablePartner . '.id', $tablePaymentPartner.'.partner_id');
            $query = $query->where($tablePaymentPartner.'.partner_id', $partnerId);
        }

        //search keyword
        if ($this->au_keyword !='') {
            $query = $query->where(function ($sql) use ($tableDescription) {
                $sql->where($tableDescription . '.name', 'like', '%' . $this->au_keyword . '%');
            });
        }
        if (count($this->au_moreWhere)) {
            foreach ($this->au_moreWhere as $key => $where) {
                if (count($where)) {
                    $query = $query->where($where[0], $where[1], $where[2]);
                }
            }
        }

        if ($this->random) {
            $query = $query->inRandomOrder();
        } else {
            if (is_array($this->au_sort) && count($this->au_sort)) {
                foreach ($this->au_sort as  $rowSort) {
                    if (is_array($rowSort) && count($rowSort) == 2) {
                        $query = $query->sort($rowSort[0], $rowSort[1]);
                    }
                }
            }
        }

        return $query;
    }
}
