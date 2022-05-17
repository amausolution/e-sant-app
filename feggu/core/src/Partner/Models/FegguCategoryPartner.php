<?php

namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FegguCategoryPartner extends Model
{
    use HasFactory;
    public $table = AU_DB_PREFIX.'category_partner';
    protected $connection = AU_CONNECTION;
    protected $guarded    = [];
    public $timestamps = false;

   use ModelTrait;

    public function partners()
    {
        return $this->hasMany(FegguPartner::class, 'category_id', 'id');
    }

    public function descriptions()
    {
        return $this->hasMany(FegguCategoryPartnerDescription::class, 'category_id', 'id');
    }

    //Function get text description
    public function getText()
    {
        return $this->descriptions()->where('lang', au_get_locale())->first();
    }
    public function getTitle()
    {
        return $this->getText()->title;
    }
    public function getDescription()
    {
        return $this->getText()->description;
    }
    public function getKeyword()
    {
        return $this->getText()->keyword;
    }
    //End  get text description




    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(function ($category) {
            //Delete category descrition
            $category->descriptions()->delete();
        });
    }

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
        return au_route('category.detail', ['alias' => $this->alias]);
    }


    public function scopeSort($query, $sortBy = null, $sortOrder = 'asc')
    {
        $sortBy = $sortBy ?? 'sort';
        return $query->orderBy($sortBy, $sortOrder);
    }

    /**
     * Get categoy detail
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
        $storeId = config('app.storeId');
        $tableDescription = (new FegguCategoryPartnerDescription)->getTable();
        $dataSelect = $this->getTable().'.*, '.$tableDescription.'.*';
        $category = $this->selectRaw($dataSelect)
            ->leftJoin($tableDescription, $tableDescription . '.category_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', au_get_locale());

        if ($type === null) {
            $category = $category->where($this->getTable().'.id', (int) $key);
        } else {
            $category = $category->where($type, $key);
        }
        $category = $category->where($this->getTable().'.status', 1);
        return $category->first();
    }



    /**
     * Start new process get data
     *
     * @return  new model
     */
    public function start()
    {
        return new FegguCategoryPartner();
    }



    /**
     * build Query
     */
    public function buildQuery()
    {
        $storeId = config('app.storeId');
        $tableDescription = (new FegguCategoryPartnerDescription)->getTable();
        $dataSelect = $this->getTable().'.*, '.$tableDescription.'.*';
        //description
        $query = $this->selectRaw($dataSelect)
            ->leftJoin($tableDescription, $tableDescription . '.category_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', au_get_locale());
        //search keyword
        if ($this->au_keyword !='') {
            $query = $query->where(function ($sql) use ($tableDescription) {
                $sql->where($tableDescription . '.title', 'like', '%' . $this->au_keyword . '%')
                    ->orWhere($tableDescription . '.keyword', 'like', '%' . $this->au_keyword . '%')
                    ->orWhere($tableDescription . '.description', 'like', '%' . $this->au_keyword . '%');
            });
        }

        $query = $query->where($this->getTable().'.status', 1);

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
    /**
     * Get list category
     *
     * @param   array  $arrOpt
     * Example: ['status' => 1, 'top' => 1]
     * @param   array  $arrSort
     * Example: ['sortBy' => 'id', 'sortOrder' => 'asc']
     * @param   array  $arrLimit  [$arrLimit description]
     * Example: ['step' => 0, 'limit' => 20]
     * @return  [type]             [return description]
     */
    public function getListAll($arrOpt = [], $arrSort = [], $arrLimit = [])
    {
        $tableDescription = (new FegguCategoryPartnerDescription)->getTable();
        $sortBy = $arrSort['sortBy'] ?? null;
        $sortOrder = $arrSort['sortOrder'] ?? 'asc';
        $step = $arrLimit['step'] ?? 0;
        $limit = $arrLimit['limit'] ?? 0;
        $data = $this
            ->leftJoin($tableDescription, $tableDescription . '.category_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', au_get_locale());

        $data = $data->sort($sortBy, $sortOrder);
        if(count($arrOpt = [])) {
            foreach ($arrOpt as $key => $value) {
                $data = $data->where($key, $value);
            }
        }
        if((int)$limit) {
            $start = $step * $limit;
            $data = $data->offset((int)$start)->limit((int)$limit);
        }
        $data = $data->get()->groupBy('parent');

        return $data;
    }
}
