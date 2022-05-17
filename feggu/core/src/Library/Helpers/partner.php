<?php
use Illuminate\Support\Str;
/**
 * Get list partner
 */
if (!function_exists('au_get_list_code_partner') && !in_array('au_get_list_code_partner', config('helper_except', []))) {
    function au_get_list_code_partner()
    {
        return \Feggu\Core\Admin\Models\AdminPartner::getListPartnerCode();
    }
}


/**
 * Get domain from code
 */
if (!function_exists('au_get_domain_from_code') && !in_array('au_get_domain_from_code', config('helper_except', []))) {
    function au_get_domain_from_code( $code)
    {
        $domainList = \Feggu\Core\Admin\Models\AdminPartner::getPartnerDomainByCode();
        if (!empty($domainList[$code])) {
            return 'http://'.$domainList[$code];
        } else {
            return url('/');
        }
    }
}

/**
 * Get domain root
 */
if (!function_exists('au_get_domain_root') && !in_array('au_get_domain_root', config('helper_except', []))) {
    function au_get_domain_root()
    {
        $partner = \Feggu\Core\Admin\Models\AdminPartner::find(AU_ID_ROOT);
        return $partner->domain;
    }
}

/**
 * Check partner is partner
 */
if (!function_exists('au_partner_is_partner') && !in_array('au_partner_is_partner', config('helper_except', []))) {
    function au_partner_is_partner( $partnerId)
    {
        $partner = \Feggu\Core\Admin\Models\AdminPartner::find($partnerId);
        if (!$partner) {
            return null;
        }
        return $partner->partner || $partnerId == AU_ID_ROOT;
    }
}

/**
 * Check partner is root
 */
if (!function_exists('au_partner_is_root') && !in_array('au_partner_is_root', config('helper_except', []))) {
    function au_partner_is_root(int $partnerId):bool
    {
        return  $partnerId == AU_ID_ROOT;
    }
}


//======== partner info============


/**
 * Get partner list of banners
 */
if (!function_exists('au_get_list_partner_of_banner') && !in_array('au_get_list_partner_of_banner', config('helper_except', []))) {
    function au_get_list_partner_of_banner(array $arrBannerId)
    {
        $tablePartner = (new \Feggu\Core\Admin\Models\AdminPartner)->getTable();
        $tableBannerPartner = (new \Feggu\Core\Partner\Models\FegguBannerPartner)->getTable();
        return \Feggu\Core\Partner\Models\FegguBannerPartner::select($tablePartner.'.code', $tablePartner.'.id', 'banner_id')
            ->leftJoin($tablePartner, $tablePartner.'.id', $tableBannerPartner.'.partner_id')
            ->whereIn('banner_id', $arrBannerId)
            ->get()
            ->groupBy('banner_id');
    }
}

/**
 * Get list partner of banner detail
 */
if (!function_exists('au_get_list_partner_of_banner_detail') && !in_array('au_get_list_partner_of_banner_detail', config('helper_except', []))) {
    function au_get_list_partner_of_banner_detail($bId):array
    {
        return \Feggu\Core\Partner\Models\FegguBannerPartner::where('banner_id', $bId)
            ->pluck('partner_id')
            ->toArray();
    }
}

/**
 * Get partner list of news
 */
if (!function_exists('au_get_list_partner_of_news') && !in_array('au_get_list_partner_of_news', config('helper_except', []))) {
    function au_get_list_partner_of_news(array $arrNewsId)
    {
        $tablePartner = (new \Feggu\Core\Admin\Models\AdminPartner)->getTable();
        $tableNewsPartner = (new \Feggu\Core\Partner\Models\FegguNewsPartner)->getTable();
        return \Feggu\Core\Partner\Models\FegguNewsPartner::select($tablePartner.'.code', $tablePartner.'.id', 'news_id')
            ->leftJoin($tablePartner, $tablePartner.'.id', $tableNewsPartner.'.partner_id')
            ->whereIn('news_id', $arrNewsId)
            ->get()
            ->groupBy('news_id');
    }
}

/**
 * Get list partner of news detail
 */
if (!function_exists('au_get_list_partner_of_news_detail') && !in_array('au_get_list_partner_of_news_detail', config('helper_except', []))) {
    function au_get_list_partner_of_news_detail($nId):array
    {
        return \Feggu\Core\Partner\Models\FegguNewsPartner::where('news_id', $nId)
            ->pluck('partner_id')
            ->toArray();
    }
}

/**
 * Get partner list of pages
 */
if (!function_exists('au_get_list_partner_of_page') && !in_array('au_get_list_partner_of_page', config('helper_except', []))) {
    function au_get_list_partner_of_page(array $arrPageId)
    {
        $tablePartner = (new \Feggu\Core\Admin\Models\AdminPartner)->getTable();
        $tablePagePartner = (new \Feggu\Core\Partner\Models\FegguPagePartner)->getTable();
        return \Feggu\Core\Partner\Models\FegguPagePartner::select($tablePartner.'.code', $tablePartner.'.id', 'page_id')
            ->leftJoin($tablePartner, $tablePartner.'.id', $tablePagePartner.'.partner_id')
            ->whereIn('page_id', $arrPageId)
            ->get()
            ->groupBy('page_id');
    }
}

/**
 * Get list partner of page detail
 */
if (!function_exists('au_get_list_partner_of_page_detail') && !in_array('au_get_list_partner_of_page_detail', config('helper_except', []))) {
    function au_get_list_partner_of_page_detail($pId):array
    {
        return \Feggu\Core\Partner\Models\FegguPagePartner::where('page_id', $pId)
            ->pluck('partner_id')
            ->toArray();
    }
}

/**
 * Get partner list of links
 */
if (!function_exists('au_get_list_partner_of_link') && !in_array('au_get_list_partner_of_link', config('helper_except', []))) {
    function au_get_list_partner_of_link($arrLinkId)
    {
        $tablePartner = (new \Feggu\Core\Admin\Models\AdminPartner)->getTable();
        $tableLinkPartner = (new \Feggu\Core\Partner\Models\FegguLinkPartner)->getTable();
        return \Feggu\Core\Partner\Models\FegguLinkPartner::select($tablePartner.'.code', $tablePartner.'.id', 'link_id')
            ->leftJoin($tablePartner, $tablePartner.'.id', $tableLinkPartner.'.partner_id')
            ->whereIn('link_id', $arrLinkId)
            ->get()
            ->groupBy('link_id');
    }
}

/**
 * Get list partner of link detail
 */
if (!function_exists('au_get_list_partner_of_link_detail') && !in_array('au_get_list_partner_of_link_detail', config('helper_except', []))) {
    function au_get_list_partner_of_link_detail($cId)
    {
        return \Feggu\Core\Partner\Models\FegguLinkPartner::where('link_id', $cId)
            ->pluck('partner_id')
            ->toArray();
    }
}


if (!function_exists('au_process_domain_partner') && !in_array('au_process_domain_partner', config('helper_except', []))) {
    /**
     * Process domain partner
     *
     * @param string|null $domain
     * @return string [string]         [$domain]
     */
    function au_process_domain_partner(string $domain = null)
    {
        $domain = str_replace(['http://', 'https://'], '', $domain);
        $domain = Str::lower($domain);
        $domain = rtrim($domain, '/');
        return $domain;
    }
}
/**
 * Get partner list of patient
 */
if (!function_exists('au_get_list_partner_of_patient')) {
    function au_get_list_partner_of_patient($arrPatientId)
    {
        $tablePartner = (new \Feggu\Core\Partner\Models\FegguPartner)->getTable();
        $tableLinkPartner = (new \Feggu\Core\Partner\Models\FegguPatient)->getTable();
        return \Feggu\Core\Partner\Models\FegguPatient::select($tablePartner.'.code', $tablePartner.'.id', 'patient_id')
            ->leftJoin($tablePartner, $tablePartner.'.id', $tableLinkPartner.'.partner_id')
            ->whereIn('patient_id', $arrPatientId)
            ->get()
            ->groupBy('patient_id');
    }
}
