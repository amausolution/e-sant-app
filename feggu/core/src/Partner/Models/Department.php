<?php


namespace Feggu\Core\Partner\Models;


use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Department extends Model
{
    use ModelTrait;

    public $timestamps = false;
    public $table = AU_DB_PREFIX.'department';
    protected $guarded = [];
    protected $connection = AU_CONNECTION;

    use UsesTenantConnection;

    public function hospitals()
    {
        return $this->belongsToMany(FegguPartner::class, DepartmentPartner::class, 'department_id', 'partner_id');
    }

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(
            function ($page) {
                $page->hospitals()->detach();
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
        return new Department();
    }

    /**
     * build Query
     */
    public function buildQuery()
    {
        $tableDescription = (new DepartmentDescription())->getTable();

        $dataSelect = $this->getTable().'.*, '.$tableDescription.'.*';
        $query = $this->selectRaw($dataSelect)
            ->leftJoin($tableDescription, $tableDescription . '.department_id', $this->getTable() . '.id')
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

    public static function getDepartment($id)
    {
        return self::where('id',$id)->first();
    }
}
