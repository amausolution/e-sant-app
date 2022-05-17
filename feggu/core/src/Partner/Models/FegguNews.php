<?php
#Feggu/Core/Partner/Models/FegguNews.php
namespace Feggu\Core\Partner\Models;

use Feggu\Core\Partner\Models\FegguNewsDescription;
use Illuminate\Database\Eloquent\Model;
use Feggu\Core\Partner\Models\FegguPartner;
use Cache;
use Feggu\Core\Partner\Models\ModelTrait;

class FegguNews extends Model
{
    use ModelTrait;

    public $table = AU_DB_PREFIX.'feggu_news';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;

    public function descriptions()
    {
        return $this->hasMany(FegguNewsDescription::class, 'news_id', 'id');
    }

    public function stores()
    {
        return $this->belongsToMany(FegguPartner::class, FegguNewsPartner::class, 'news_id', 'partner_id');
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
    /**
     * [getUrl description]
     * @return [type] [description]
     */
    public function getUrl()
    {
        return sc_route('news.detail', ['alias' => $this->alias]);
    }


    public function scopeSort($query, $sortBy = null, $sortOrder = 'asc')
    {
        $sortBy = $sortBy ?? 'sort';
        return $query->orderBy($sortBy, $sortOrder);
    }


    /**
     * Get news detail
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
        $tableDescription = (new FegguNewsDescription)->getTable();
        $dataSelect = $this->getTable().'.*, '.$tableDescription.'.*';
        $news = $this->selectRaw($dataSelect)
            ->leftJoin($tableDescription, $tableDescription . '.news_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', au_get_locale());

        $partnerId = config('app.partnerId');
        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            $tableNewsStore = (new FegguNewsPartner)->getTable();
            $tableStore = (new FegguPartner)->getTable();
            $news = $news->join($tableNewsStore, $tableNewsStore.'.news_id', $this->getTable() . '.id');
            $news = $news->join($tableStore, $tableStore . '.id', $tableNewsStore.'.partner_id');
            $news = $news->where($tableStore . '.status', '1');
            $news = $news->where($tableNewsStore.'.partner_id', $partnerId);
        }

        if ($type === null) {
            $news = $news->where($this->getTable() .'.id', (int) $key);
        } else {
            $news = $news->where($type, $key);
        }
        $news = $news->where($this->getTable() .'.status', 1)
            ->first();
        return $news;
    }

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(
            function ($news) {
                $news->descriptions()->delete();
                $news->stores()->detach();
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
        return new FegguNews;
    }

    /**
     * build Query
     */
    public function buildQuery()
    {
        $tableDescription = (new FegguNewsDescription)->getTable();

        $dataSelect = $this->getTable().'.*, '.$tableDescription.'.*';
        $query = $this->selectRaw($dataSelect)
            ->leftJoin($tableDescription, $tableDescription . '.news_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', au_get_locale());
        //search keyword
        if ($this->sc_keyword !='') {
            $query = $query->where(function ($sql) use ($tableDescription) {
                $sql->where($tableDescription . '.title', 'like', '%' . $this->sc_keyword . '%')
                ->orWhere($tableDescription . '.keyword', 'like', '%' . $this->sc_keyword . '%')
                ->orWhere($tableDescription . '.description', 'like', '%' . $this->sc_keyword . '%');
            });
        }

        $partnerId = config('app.partnerId');
        if (au_config_global('MultiStorePro') || au_config_global('MultiVendorPro')) {
            $tableNewsStore = (new FegguNewsPartner)->getTable();
            $tableStore = (new FegguPartner)->getTable();
            $query = $query->join($tableNewsStore, $tableNewsStore.'.news_id', $this->getTable() . '.id');
            $query = $query->join($tableStore, $tableStore . '.id', $tableNewsStore.'.partner_id');
            $query = $query->where($tableStore . '.status', '1');
            $query = $query->where($tableNewsStore.'.partner_id', $partnerId);
        }

        $query = $query->where($this->getTable() .'.status', 1);

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
        }
        //Use field "sort" if haven't above
        if (!$ckeckSort) {
            $query = $query->orderBy($this->getTable().'.sort', 'asc');
        }
        //Default, will sort id
        $query = $query->orderBy($this->getTable().'.id', 'desc');

        return $query;
    }
}
