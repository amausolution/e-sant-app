<?php


namespace Feggu\Core\Partner\Models;


use Feggu\Core\Partner\Models\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class FegguProfession extends Model
{
    use ModelTrait,UsesTenantConnection;

    public $timestamps     = false;
    public $table          = AU_DB_PREFIX.'feggu_profession';
    protected $connection  = AU_CONNECTION;
    protected $guarded     = [];

    protected $appends = ['title'];
    public function descriptions()
    {
        return $this->hasMany(FegguProfessionDescription::class, 'profession_id', 'id');
    }

    public function doctors()
    {
        return $this->hasMany(PartnerUser::class,'profession','id');
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
    public function getTitleAttribute()
    {
        return $this->getText()->title ?? '';
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
        $tableDescription = (new FegguProfessionDescription)->getTable();

        $dataSelect = $this->getTable().'.*, '.$tableDescription.'.*';
        $page = $this->selectRaw($dataSelect)
            ->leftJoin($tableDescription, $tableDescription . '.profession_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', au_get_locale());


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
            }
        );
    }


    /**
     * Start new process get data
     *
     * @return FegguProfession
     */
    public function start()
    {
        return new FegguProfession();
    }

    /**
     * build Query
     */
    public function buildQuery()
    {
        $tableDescription = (new FegguProfession())->getTable();

        $dataSelect = $this->getTable().'.*, '.$tableDescription.'.*';
        $query = $this->selectRaw($dataSelect)
            ->leftJoin($tableDescription, $tableDescription . '.profession_id', $this->getTable() . '.id')
            ->where($tableDescription . '.lang', au_get_locale());


        //search keyword
        if ($this->au_keyword !='') {
            $query = $query->where(function ($sql) use ($tableDescription) {
                $sql->where($tableDescription . '.title', 'like', '%' . $this->au_keyword . '%');
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
