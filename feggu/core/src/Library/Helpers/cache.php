<?php

use Feggu\Core\Partner\Models\FegguLanguage;
use \Illuminate\Support\Facades\Cache;

if (!function_exists('au_clear_cache') && !in_array('au_get_plugin_captcha_installed', config('helper_except', []))) {
    /**
     * Clear cache
     *
     * @param string $typeCache
     * @param   [string]  $domain
     *
     * @return array [string]         [$domain]
     */
    function au_clear_cache($typeCache = 'cache_all', $partnerId = null)
    {
        try {
            $storeI = $partnerId ?? session('adminPartnerId');
            if ($typeCache == 'cache_all') {
                Cache::flush();
            } else {
                $arrCacheLocal = [];
                $arrLang = FegguLanguage::getCodeAll();
                foreach ($arrLang as $code => $name) {
                    $arrCacheLocal['cache_category'][] = 'cache_category_'.$code;
                    $arrCacheLocal['cache_product'][] = 'cache_product_'.$code;
                    $arrCacheLocal['cache_news'][] = 'cache_news_'.$code;
                    $arrCacheLocal['cache_category_cms'][] = 'cache_category_cms_'.$code;
                    $arrCacheLocal['cache_content_cms'][] = 'cache_content_cms_'.$code;
                    $arrCacheLocal['cache_page'][] = 'cache_page_'.$code;
                }
                Cache::forget($typeCache);
                if (!empty($arrCacheLocal[$typeCache])) {
                    foreach ($arrCacheLocal[$typeCache] as  $cacheIndex) {
                        Cache::forget($cacheIndex);
                        Cache::forget($storeI.'_'.$cacheIndex);
                    }
                }
            }
            $response = ['error' => 0, 'msg' => 'Clear success!', 'action' => $typeCache];
        } catch (\Throwable $e) {
            $response = ['error' => 1, 'msg' => $e->getMessage(), 'action' => $typeCache];
        }
        return $response;
    }
}

if (!function_exists('au_set_cache') && !in_array('au_get_plugin_captcha_installed', config('helper_except', []))) {
    /**
     * [au_set_cache description]
     *
     * @param   [string]$cacheIndex  [$cacheIndex description]
     * @param   [type]$value       [$value description]
     * @param   [seconds]$time        [$time description]
     * @param   null               [ description]
     *
     * @return  [type]             [return description]
     */
    function au_set_cache($cacheIndex, $value, $time = null)
    {
        if (empty($cacheIndex)) {
            return ;
        }
        $seconds = $time ?? (au_config_global('cache_time') ?? 600);

        Cache::put($cacheIndex, $value, $seconds);
    }
}
