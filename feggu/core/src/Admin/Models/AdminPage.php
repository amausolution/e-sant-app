<?php

namespace Feggu\Core\Admin\Models;

use Feggu\Core\Partner\Models\FegguPage;
use Cache;
use Feggu\Core\Partner\Models\FegguPageDescription;
use Feggu\Core\Partner\Models\FegguPagePartner;

class AdminPage extends FegguPage
{
    protected static $getListTitleAdmin = null;
    protected static $getListPageGroupByParentAdmin = null;
    /**
     * Get page detail in admin
     *
     * @param   [type]  $id  [$id description]
     *
     * @return  [type]       [return description]
     */
    public static function getPageAdmin($id, $partnerId = null)
    {
        $data = self::where('id', $id);
        if ($partnerId) {
            $tablePageStore = (new FegguPagePartner)->getTable();
            $tablePage = (new FegguPage)->getTable();
            $data = $data->leftJoin($tablePageStore, $tablePageStore . '.page_id', $tablePage . '.id');
            $data = $data->where($tablePageStore . '.partner_id', $partnerId);
        }
        $data = $data->first();
        return $data;
    }

    /**
     * Get list page in admin
     *
     * @param   [array]  $dataSearch  [$dataSearch description]
     *
     * @return  [type]               [return description]
     */
    public static function getPageListAdmin(array $dataSearch, $partnerId = null)
    {
        $keyword          = $dataSearch['keyword'] ?? '';
        $sort_order       = $dataSearch['sort_order'] ?? '';
        $arrSort          = $dataSearch['arrSort'] ?? '';
        $tableDescription = (new FegguPageDescription)->getTable();
        $tablePage     = (new AdminPage)->getTable();

        $pageList = (new FegguPage)
            ->leftJoin($tableDescription, $tableDescription . '.page_id', $tablePage . '.id')
            ->where($tableDescription . '.lang', au_get_locale());

        $tablePage = (new FegguPage)->getTable();
        if ($partnerId) {
            $tablePageStore = (new FegguPagePartner)->getTable();
            $pageList = $pageList->leftJoin($tablePageStore, $tablePageStore . '.page_id', $tablePage . '.id');
            $pageList = $pageList->where($tablePageStore . '.partner_id', $partnerId);
        }

        if ($keyword) {
            $pageList = $pageList->where(function ($sql) use ($tableDescription, $keyword) {
                $sql->where($tableDescription . '.title', 'like', '%' . $keyword . '%');
            });
        }

        if ($sort_order && array_key_exists($sort_order, $arrSort)) {
            $field = explode('__', $sort_order)[0];
            $sort_field = explode('__', $sort_order)[1];
            $pageList = $pageList->orderBy($field, $sort_field);
        } else {
            $pageList = $pageList->orderBy($tablePage.'.id', 'desc');
        }
        $pageList = $pageList->paginate(20);

        return $pageList;
    }


    /**
     * Get array title page
     * user for admin
     *
     * @return  [type]  [return description]
     */
    public static function getListTitleAdmin($partnerId = null)
    {
        $storeCache = $partnerId ? $partnerId : session('adminPartnerId');
        $tableDescription = (new FegguPageDescription)->getTable();
        $table = (new AdminPage)->getTable();
        if (au_config_global('cache_status') && au_config_global('cache_page')) {
            if (!Cache::has($storeCache.'_cache_page_'.au_get_locale())) {
                if (self::$getListTitleAdmin === null) {
                    $data = self::join($tableDescription, $tableDescription.'.page_id', $table.'.id')
                    ->where('lang', au_get_locale());
                    if ($partnerId) {
                        $tablePageStore = (new FegguPagePartner)->getTable();
                        $data = $data->leftJoin($tablePageStore, $tablePageStore . '.page_id', $table . '.id');
                        $data = $data->where($tablePageStore . '.partner_id', $partnerId);
                    }
                    $data = $data->pluck('title', 'id')->toArray();
                    self::$getListTitleAdmin = $data;
                }
                au_set_cache($storeCache.'_cache_page_'.au_get_locale(), self::$getListTitleAdmin);
            }
            return Cache::get($storeCache.'_cache_page_'.au_get_locale());
        } else {
            if (self::$getListTitleAdmin === null) {
                $data = self::join($tableDescription, $tableDescription.'.page_id', $table.'.id')
                ->where('lang', au_get_locale());
                if ($partnerId) {
                    $tablePageStore = (new FegguPagePartner)->getTable();
                    $data = $data->leftJoin($tablePageStore, $tablePageStore . '.page_id', $table . '.id');
                    $data = $data->where($tablePageStore . '.partner_id', $partnerId);
                }
                $data = $data->pluck('title', 'id')->toArray();
                self::$getListTitleAdmin = $data;
            }
            return self::$getListTitleAdmin;
        }
    }


    /**
     * Create a new page
     *
     * @param   array  $dataInsert  [$dataInsert description]
     *
     * @return  [type]              [return description]
     */
    public static function createPageAdmin(array $dataInsert)
    {
        return self::create($dataInsert);
    }


    /**
     * Insert data description
     *
     * @param   array  $dataInsert  [$dataInsert description]
     *
     * @return  [type]              [return description]
     */
    public static function insertDescriptionAdmin(array $dataInsert)
    {
        return FegguPageDescription::create($dataInsert);
    }

    /**
     * [getListPageAlias description]
     *
     * @param   [type]  $partnerId  [$partnerId description]
     *
     * @return  array             [return description]
     */
    public function getListPageAlias($partnerId = null):array
    {
        $arrReturn = ['' => au_language_render('admin.config_layout.home_page_default_empty')];
        $tablePage = $this->getTable();
        $tablePageStore = (new FegguPagePartner)->getTable();
        $data = $this;
        if ($partnerId) {
            $data = $this->leftJoin($tablePageStore, $tablePageStore . '.page_id', $tablePage . '.id');
            $data = $data->where($tablePageStore . '.partner_id', $partnerId);
        }
        $data = $data->pluck('alias')->toArray();
        if (count($data)) {
            foreach ($data as $key => $value) {
                $arrReturn[$value] = $value;
            }
        }
        return $arrReturn;
    }
}
