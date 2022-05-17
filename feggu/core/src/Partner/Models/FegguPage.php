<?php
#Feggu/Core/Partner/Models/FegguPage.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\ModelTrait;

class FegguPage extends Model
{
    use ModelTrait;

    public $timestamps     = false;
    public $table          = AU_DB_PREFIX.'feggu_page';
    protected $connection  = AU_CONNECTION;
    protected $guarded     = [];

    public function stores()
    {
        return $this->belongsToMany(FegguPartner::class, FegguPagePartner::class, 'page_id', 'partner_id');
    }

    public function descriptions()
    {
        return $this->hasMany(FegguPageDescription::class, 'page_id', 'id');
    }

    //Function get text description
    public function getText()
    {
        return $this->descriptions()->where('lang', au_get_locale())->first();
    }
    public function getTitle()
    {
        return $this->getText()->title ?? '';
    }
    public function getDescription()
    {
        return $this->getText()->description ?? '';
    }
    public function getKeyword()
    {
        return $this->getText()->keyword?? '';
    }
    public function getContent()
    {
        return $this->getText()->content;
    }
    //End  get text description


    /*
    *Get thumb
    */
    public function getThumb()
    {
        return au_image_get_path_thumb($this->image);
    }

    /*
    *Get image
    */
    public function getImage()
    {
        return au_image_get_path($this->image);
    }

    public function getUrl()
    {
        return au_route('page.detail', ['alias' => $this->alias]);
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
        $tableDescription = (new FegguPageDescription)->getTable();

        $dataSelect = $this->getTable().'.*, '.$tableDescription.'.*';
        $page = $this->selectRaw($dataSelect)
            ->leftJoin($tableDescription, $tableDescription . '.page_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', au_get_locale());

        $partnerId = config('app.partnerId');
        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            $tablePageStore = (new FegguPagePartner)->getTable();
            $tableStore = (new FegguPartner)->getTable();
            $page = $page->join($tablePageStore, $tablePageStore.'.page_id', $this->getTable() . '.id');
            $page = $page->join($tableStore, $tableStore . '.id', $tablePageStore.'.partner_id');
            $page = $page->where($tableStore . '.status', '1');
            $page = $page->where($tablePageStore.'.partner_id', $partnerId);
        }

        if ($type === null) {
            $page = $page->where($this->getTable() .'.id', (int) $key);
        } else {
            $page = $page->where($type, $key);
        }
        $page = $page->where($this->getTable() .'.status', 1);

        return $page->first();
    }

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(
            function ($page) {
                $page->descriptions()->delete();
                $page->stores()->detach();
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
        return new FegguPage;
    }

    /**
     * build Query
     */
    public function buildQuery()
    {
        $tableDescription = (new FegguPageDescription)->getTable();

        $dataSelect = $this->getTable().'.*, '.$tableDescription.'.*';
        $query = $this->selectRaw($dataSelect)
            ->leftJoin($tableDescription, $tableDescription . '.page_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', au_get_locale());

        $partnerId = config('app.partnerId');
        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            $tablePageStore = (new FegguPagePartner)->getTable();
            $tableStore = (new FegguPartner)->getTable();
            $query = $query->join($tablePageStore, $tablePageStore.'.page_id', $this->getTable() . '.id');
            $query = $query->join($tableStore, $tableStore . '.id', $tablePageStore.'.partner_id');
            $query = $query->where($tableStore . '.status', '1');
            $query = $query->where($tablePageStore.'.partner_id', $partnerId);
        }

        //search keyword
        if ($this->au_keyword !='') {
            $query = $query->where(function ($sql) use ($tableDescription) {
                $sql->where($tableDescription . '.title', 'like', '%' . $this->au_keyword . '%')
                ->orWhere($tableDescription . '.keyword', 'like', '%' . $this->au_keyword . '%')
                ->orWhere($tableDescription . '.description', 'like', '%' . $this->au_keyword . '%');
            });
        }

        $query = $query->where($this->getTable() .'status', 1);

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
