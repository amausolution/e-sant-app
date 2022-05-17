<?php
namespace Feggu\Core\Partner\Models;

use Feggu\Core\Partner\Models\ShopProduct;
use Illuminate\Database\Eloquent\Model;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\ModelTrait;

class FegguBrand extends Model
{
    use ModelTrait;

    public $timestamps = false;
    public $table = AU_DB_PREFIX.'shop_brand';
    protected $guarded = [];
    private static $getList = null;
    protected $connection = AU_CONNECTION;


    public static function getListAll()
    {
        if (self::$getList === null) {
            self::$getList = self::get()->keyBy('id');
        }
        return self::$getList;
    }

    public function products()
    {
        return $this->hasMany(ShopProduct::class, 'brand_id', 'id');
    }

    public function stores()
    {
        return $this->belongsToMany(FegguPartner::class, ShopBrandStore::class, 'brand_id', 'partner_id');
    }

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(function ($brand) {
            $brand->stores()->detach();
        });
    }

    /**
     * [getUrl description]
     * @return [type] [description]
     */
    public function getUrl()
    {
        return sc_route('brand.detail', ['alias' => $this->alias]);
    }

    /*
    Get thumb
    */
    public function getThumb()
    {
        return sc_image_get_path_thumb($this->image);
    }

    /*
    Get image
    */
    public function getImage()
    {
        return sc_image_get_path($this->image);
    }


    public function scopeSort($query, $sortBy = null, $sortOrder = 'asc')
    {
        $sortBy = $sortBy ?? 'sort';
        return $query->orderBy($sortBy, $sortOrder);
    }


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
        $partnerId = config('app.partnerId');
        $dataSelect = $this->getTable().'.*';
        $data = $this->selectRaw($dataSelect);
        if ($type === null) {
            $data = $data->where($this->getTable().'.id', (int) $key);
        } else {
            $data = $data->where($type, $key);
        }
        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            $tableBrandStore = (new ShopBrandStore)->getTable();
            $tableStore = (new FegguPartner)->getTable();
            $data = $data->join($tableBrandStore, $tableBrandStore.'.brand_id', $this->getTable() . '.id');
            $data = $data->join($tableStore, $tableStore . '.id', $tableBrandStore.'.partner_id');
            $data = $data->where($tableStore . '.status', '1');
            $data = $data->where($tableBrandStore.'.partner_id', $partnerId);
        }

        $data = $data->where($this->getTable().'.status', 1);
        return $data->first();
    }


    /**
     * Start new process get data
     *
     * @return  new model
     */
    public function start()
    {
        return new FegguBrand;
    }

    /**
     * build Query
     */
    public function buildQuery()
    {
        $partnerId = config('app.partnerId');
        $dataSelect = $this->getTable().'.*';
        $query = $this->selectRaw($dataSelect)
            ->where($this->getTable().'.status', 1);

        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            $tableBrandStore = (new ShopBrandStore)->getTable();
            $tableStore = (new FegguPartner)->getTable();
            $query = $query->join($tableBrandStore, $tableBrandStore.'.brand_id', $this->getTable() . '.id');
            $query = $query->join($tableStore, $tableStore . '.id', $tableBrandStore.'.partner_id');
            $query = $query->where($tableStore . '.status', '1');
            $query = $query->where($tableBrandStore.'.partner_id', $partnerId);
        }

        if (count($this->sc_moreWhere)) {
            foreach ($this->sc_moreWhere as $key => $where) {
                if (count($where)) {
                    $query = $query->where($where[0], $where[1], $where[2]);
                }
            }
        }
        if ($this->sc_random) {
            $query = $query->inRandomOrder();
        } else {
            $ckeckSort = false;
            if (is_array($this->sc_sort) && count($this->sc_sort)) {
                foreach ($this->sc_sort as  $rowSort) {
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
