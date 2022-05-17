<?php

namespace Feggu\Core\Admin\Models;

use Feggu\Core\Partner\Models\FegguNews;
use Cache;
use Feggu\Core\Partner\Models\FegguNewsDescription;
use Feggu\Core\Partner\Models\FegguNewsPartner;

class AdminNews extends FegguNews
{
    protected static $getListTitleAdmin = null;
    protected static $getListNewsGroupByParentAdmin = null;
    /**
     * Get news detail in admin
     *
     * @param   [type]  $id  [$id description]
     *
     * @return  [type]       [return description]
     */
    public static function getNewsAdmin($id, $partnerId = null)
    {
        $data = self::where('id', $id);
        if ($partnerId) {
            $tableNewsStore = (new FegguNewsPartner)->getTable();
            $tableNews = (new FegguNews)->getTable();
            $data = $data->leftJoin($tableNewsStore, $tableNewsStore . '.news_id', $tableNews . '.id');
            $data = $data->where($tableNewsStore . '.partner_id', $partnerId);
        }
        $data = $data->first();
        return $data;
    }

    /**
     * Get list news in admin
     *
     * @param   [array]  $dataSearch  [$dataSearch description]
     *
     * @return  [type]               [return description]
     */
    public static function getNewsListAdmin(array $dataSearch, $partnerId = null)
    {
        $keyword          = $dataSearch['keyword'] ?? '';
        $sort_order       = $dataSearch['sort_order'] ?? '';
        $arrSort          = $dataSearch['arrSort'] ?? '';
        $tableDescription = (new FegguNewsDescription)->getTable();
        $tableNews     = (new FegguNews)->getTable();

        $newsList = (new FegguNews)
            ->leftJoin($tableDescription, $tableDescription . '.news_id', $tableNews . '.id')
            ->where($tableDescription . '.lang', au_get_locale());

        $tableNews = (new FegguNews)->getTable();
        if ($partnerId) {
            $tableNewsStore = (new FegguNewsPartner)->getTable();
            $newsList = $newsList->leftJoin($tableNewsStore, $tableNewsStore . '.news_id', $tableNews . '.id');
            $newsList = $newsList->where($tableNewsStore . '.partner_id', $partnerId);
        }

        if ($keyword) {
            $newsList = $newsList->where(function ($sql) use ($tableDescription, $keyword) {
                $sql->where($tableDescription . '.title', 'like', '%' . $keyword . '%');
            });
        }

        if ($sort_order && array_key_exists($sort_order, $arrSort)) {
            $field = explode('__', $sort_order)[0];
            $sort_field = explode('__', $sort_order)[1];
            $newsList = $newsList->orderBy($field, $sort_field);
        } else {
            $newsList = $newsList->orderBy($tableNews.'.id', 'desc');
        }
        $newsList = $newsList->paginate(20);

        return $newsList;
    }


    /**
     * Get array title news
     * user for admin
     *
     * @return  [type]  [return description]
     */
    public static function getListTitleAdmin($partnerId = null)
    {
        $storeCache = $partnerId ? $partnerId : session('adminPartnerId');
        $tableDescription = (new FegguNewsDescription)->getTable();
        $table = (new AdminNews)->getTable();
        if (au_config_global('cache_status') && au_config_global('cache_news')) {
            if (!Cache::has($storeCache.'_cache_news_'.au_get_locale())) {
                if (self::$getListTitleAdmin === null) {
                    $data = self::join($tableDescription, $tableDescription.'.news_id', $table.'.id')
                    ->where('lang', au_get_locale());
                    if ($partnerId) {
                        $tableNewsStore = (new FegguNewsPartner)->getTable();
                        $data = $data->leftJoin($tableNewsStore, $tableNewsStore . '.news_id', $table . '.id');
                        $data = $data->where($tableNewsStore . '.partner_id', $partnerId);
                    }
                    $data = $data->pluck('title', 'id')->toArray();
                    self::$getListTitleAdmin = $data;
                }
                au_set_cache($storeCache.'_cache_news_'.au_get_locale(), self::$getListTitleAdmin);
            }
            return Cache::get($storeCache.'_cache_news_'.au_get_locale());
        } else {
            if (self::$getListTitleAdmin === null) {
                $data = self::join($tableDescription, $tableDescription.'.news_id', $table.'.id')
                ->where('lang', au_get_locale());
                if ($partnerId) {
                    $tableNewsStore = (new FegguNewsPartner)->getTable();
                    $data = $data->leftJoin($tableNewsStore, $tableNewsStore . '.news_id', $table . '.id');
                    $data = $data->where($tableNewsStore . '.partner_id', $partnerId);
                }
                $data = $data->pluck('title', 'id')->toArray();
                self::$getListTitleAdmin = $data;
            }
            return self::$getListTitleAdmin;
        }
    }


    /**
     * Create a new news
     *
     * @param   array  $dataInsert  [$dataInsert description]
     *
     * @return  [type]              [return description]
     */
    public static function createNewsAdmin(array $dataInsert)
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
        return FegguNewsDescription::create($dataInsert);
    }

    /**
    * Get total news of system
    *
    * @return  [type]  [return description]
    */
    public static function getTotalNews()
    {
        return self::count();
    }
}
