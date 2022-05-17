<?php

namespace Feggu\Core\Partner\Models;

/**
 * Trait Model.
 */
trait ModelTrait
{
    protected $au_limit = 'all'; // all or interger
    protected $au_paginate = 0; // 0: dont paginate,
    protected $au_sort = [];
    protected $au_moreWhere = []; // more wehere
    protected $au_random = 0; // 0: no random, 1: random
    protected $au_keyword = ''; // search search product



    /**
     * Set value limit
     * @param   [string]  $limit
     */
    public function setLimit($limit)
    {
        if ($limit === 'all') {
            $this->au_limit = $limit;
        } else {
            $this->au_limit = (int)$limit;
        }
        return $this;
    }

    /**
     * Set value sort
     * @param   [array]  $sort ['field', 'asc|desc']
     */
    public function setSort(array $sort)
    {
        if (is_array($sort)) {
            $this->au_sort[] = $sort;
        }
        return $this;
    }

    /**
     * Add more where
     * @param   [array]  $moreWhere
     */
    public function setMoreWhere(array $moreWhere)
    {
        if (is_array($moreWhere)) {
            if (count($moreWhere) == 2) {
                $where[0] = $moreWhere[0];
                $where[1] = '=';
                $where[2] = $moreWhere[1];
            } elseif (count($moreWhere) == 3) {
                $where = $moreWhere;
            }
            if (count($where) == 3) {
                $this->au_moreWhere[] = $where;
            }
        }
        return $this;
    }

    /**
     * Enable paginate mode
     *  0 - no paginate
     */
    public function setPaginate()
    {
        $this->au_paginate = 1;
        return $this;
    }

    /**
     * Set random mode
     */
    public function setRandom()
    {
        $this->au_random = 1;
        return $this;
    }

    /**
     * Set keyword search
     * @param   [string]  $keyword
     */
    public function setKeyword($keyword)
    {
        if (trim($keyword)) {
            $this->au_keyword = trim($keyword);
        }
        return $this;
    }


    /**
    * Get Sql
    */
    public function getSql()
    {
        $query = $this->buildQuery();
        if (!$this->au_paginate) {
            if ($this->au_limit !== 'all') {
                $query = $query->limit($this->au_limit);
            }
        }
        return $query = $query->toSql();
    }

    /**
    * Get data
    * @param   [array]  $action
    */
    public function getData(array $action = [])
    {
        $query = $this->buildQuery();
        if (!empty($action['query'])) {
            return $query;
        }
        if ($this->au_paginate) {
            $data =  $query->paginate(($this->au_limit === 'all') ? 20 : $this->au_limit);
        } else {
            if ($this->au_limit !== 'all') {
                $query = $query->limit($this->au_limit);
            }
            $data = $query->get();

            if (!empty($action['keyBy'])) {
                $data = $data->keyBy($action['keyBy']);
            }
            if (!empty($action['groupBy'])) {
                $data = $data->groupBy($action['groupBy']);
            }
        }
        return $data;
    }

    /**
     * Get full data
     *
     * @return  [type]  [return description]
     */
    public function getFull()
    {
        if (method_exists($this, 'getDetail')) {
            return $this->getDetail($this->id);
        } else {
            return $this;
        }
    }


}
