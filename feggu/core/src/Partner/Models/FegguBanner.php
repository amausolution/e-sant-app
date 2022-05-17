<?php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\ModelTrait;

class FegguBanner extends Model
{
    use ModelTrait;

    public $table = AU_DB_PREFIX.'feggu_banner';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;

    protected $au_type = 'all'; // all or interger
    protected $au_store = 0; // 1: only produc promotion,

    public function stores()
    {
        return $this->belongsToMany(FegguPartner::class, FegguBannerPartner::class, 'banner_id', 'partner_id');
    }

    /*
    Get thumb
    */
    public function getThumb()
    {
        return au_image_get_path_thumb($this->image);
    }

    /*
    Get image
    */
    public function getImage()
    {
        return au_image_get_path($this->image);
    }

    public function scopeSort($query, $sortBy = null, $sortOrder = 'asc')
    {
        $sortBy = $sortBy ?? 'sort';
        return $query->orderBy($sortBy, $sortOrder);
    }

    /**
     * Get info detail
     *
     * @param   [int]  $id
     * @param   [int]  $status
     *
     */
    public function getDetail($id)
    {
        $partnerId = config('app.partnerId');
        $dataSelect = $this->getTable().'.*';
        $data =  $this->selectRaw($dataSelect)
            ->where('id', (int)$id)->where($this->getTable() .'.status', 1);
        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            $tableBannerStore = (new FegguBannerPartner)->getTable();
            $tableStore = (new FegguPartner)->getTable();
            $data = $data->join($tableBannerStore, $tableBannerStore.'.banner_id', $this->getTable() . '.id');
            $data = $data->join($tableStore, $tableStore . '.id', $tableBannerStore.'.partner_id');
            $data = $data->where($tableStore . '.status', '1');
            $data = $data->where($tableBannerStore.'.partner_id', $partnerId);
        }
        $data = $data->first();
        return $data;
    }

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(function ($banner) {
            $banner->stores()->detach();
        });
    }


    /**
     * Start new process get data
     *
     * @return  new model
     */
    public function start()
    {
        return new FegguBanner;
    }

    /**
     * Set type
     */
    public function setType($type)
    {
        $this->au_type = $type;
        return $this;
    }

    /**
     * Get banner
     */
    public function getBanner()
    {
        $this->setType('banner');
        return $this;
    }

    /**
     * Get banner
     */
    public function getBannerStore()
    {
        $this->setType('banner-store');
        return $this;
    }

    /**
     * Get background
     */
    public function getBackground()
    {
        $this->setType('background');
        $this->setLimit(1);
        return $this;
    }

    /**
     * Get background
     */
    public function getBackgroundStore()
    {
        $this->setType('background-store');
        $this->setLimit(1);
        return $this;
    }

    /**
     * Get banner
     */
    public function getBreadcrumb()
    {
        $this->setType('breadcrumb');
        $this->setLimit(1);
        return $this;
    }

    /**
     * Get banner
     */
    public function getBreadcrumbStore()
    {
        $this->setType('breadcrumb-store');
        $this->setLimit(1);
        return $this;
    }

    /**
     * Set store id
     *
     */
    public function setStore($id)
    {
        $this->au_partner = (int)$id;
        return $this;
    }

    /**
     * build Query
     */
    public function buildQuery()
    {
        $dataSelect = $this->getTable().'.*';
        $query =  $this->selectRaw($dataSelect)
            ->where($this->getTable() .'.status', 1);

        $partnerId = config('app.partnerId');

        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            //Get product active for partner
            if (!empty($this->au_partner)) {
                //If sepcify partner id
                $partnerId = $this->au_partner;
            }
            $tableBannerStore = (new FegguBannerPartner)->getTable();
            $tableStore = (new FegguPartner)->getTable();
            $query = $query->join($tableBannerStore, $tableBannerStore.'.banner_id', $this->getTable() . '.id');
            $query = $query->join($tableStore, $tableStore . '.id', $tableBannerStore.'.partner_id');
            $query = $query->where($tableStore . '.status', '1');
            $query = $query->where($tableBannerStore.'.partner_id', $partnerId);
        }

        if ($this->au_type !== 'all') {
            $query = $query->where('type', $this->au_type);
        }

        if (count($this->au_moreWhere)) {
            foreach ($this->au_moreWhere as $key => $where) {
                if (count($where)) {
                    $query = $query->where($where[0], $where[1], $where[2]);
                }
            }
        }

        if ($this->au_random) {
            $query = $query->inRandomOrder();
        } else {
            $ckeckSort = false;
            if (is_array($this->au_sort) && count($this->au_sort)) {
                foreach ($this->au_sort as  $rowSort) {
                    if (is_array($rowSort) && count($rowSort) == 2) {
                        if ($rowSort[0] == 'sort') {
                            $ckeckSort = true;
                        }
                        $query = $query->sort($rowSort[0], $rowSort[1]);
                    }
                }
            }
            //Use field "sort" if haven't above
            if (!$ckeckSort) {
                $query = $query->orderBy($this->getTable().'.sort', 'asc');
            }
            //Default, will sort id
            $query = $query->orderBy($this->getTable().'.id', 'desc');
        }

        return $query;
    }
}
