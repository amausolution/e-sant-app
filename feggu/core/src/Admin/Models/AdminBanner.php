<?php

namespace Feggu\Core\Admin\Models;

use Feggu\Core\Partner\Models\FegguBanner;
use Feggu\Core\Partner\Models\FegguBannerPartner;

class AdminBanner extends FegguBanner
{
    /**
     * Get banner detail in admin
     *
     * @param   [type]  $id  [$id description]
     *
     * @return  [type]       [return description]
     */
    public static function getBannerAdmin($id, $partnerId = null)
    {
        $data = self::where('id', $id);
        if ($partnerId) {
            $tableBannerStore = (new FegguBannerPartner)->getTable();
            $tableBanner = (new FegguBanner)->getTable();
            $data = $data->leftJoin($tableBannerStore, $tableBannerStore . '.banner_id', $tableBanner . '.id');
            $data = $data->where($tableBannerStore . '.partner_id', $partnerId);
        }
        $data = $data->first();
        return $data;
    }

    /**
     * Get list banner in admin
     *
     * @param   [array]  $dataSearch  [$dataSearch description]
     *
     * @return  [type]               [return description]
     */
    public static function getBannerListAdmin(array $dataSearch, $partnerId = null)
    {
        $sort_order       = $dataSearch['sort_order'] ?? '';
        $arrSort          = $dataSearch['arrSort'] ?? '';
        $keyword          = $dataSearch['keyword'] ?? '';
        $bannerList = (new FegguBanner);
        $tableBanner = $bannerList->getTable();
        if ($partnerId) {
            $tableBannerStore = (new FegguBannerPartner)->getTable();
            $bannerList = $bannerList->leftJoin($tableBannerStore, $tableBannerStore . '.banner_id', $tableBanner . '.id');
            $bannerList = $bannerList->where($tableBannerStore . '.partner_id', $partnerId);
        }
        if ($keyword) {
            $bannerList->where($tableBanner.'.title', 'like', '%'.$keyword.'%');
        }
        if ($sort_order && array_key_exists($sort_order, $arrSort)) {
            $field = explode('__', $sort_order)[0];
            $sort_field = explode('__', $sort_order)[1];
            $bannerList = $bannerList->sort($field, $sort_field);
        } else {
            $bannerList = $bannerList->sort($tableBanner.'.id', 'desc');
        }
        $bannerList = $bannerList->paginate(20);

        return $bannerList;
    }

    /**
     * Create a new banner
     *
     * @param   array  $dataInsert  [$dataInsert description]
     *
     * @return  [type]              [return description]
     */
    public static function createBannerAdmin(array $dataInsert)
    {
        return self::create($dataInsert);
    }
}
