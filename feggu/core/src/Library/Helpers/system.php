<?php
use Feggu\Core\Admin\Models\AdminConfig;
use Feggu\Core\Admin\Models\AdminPartner;
use Feggu\Core\Partner\Models\FegguPartnerBlockContent;
use Feggu\Core\Partner\Models\FegguLink;
use Feggu\Core\Partner\Models\FegguPartnerCss;
use Illuminate\Support\Arr;

if (!function_exists('au_admin_can_config')) {
    /**
     * Check user can change config value
     *
     * @return  [type]          [return description]
     */
    function au_admin_can_config()
    {
        return \Feggu\Core\Admin\Admin::user()->checkPermissionConfig();
    }
}

if (!function_exists('au_config') && !in_array('au_config', config('helper_except', []), true)) {
    /**
     * Get value config from table au_config
     * Default value is only used if the config key does not exist (including null values)
     *
     * @param   [string|array|null]  $key      [$key description]
     * @param   [int|null]  $partnerId  [$partnerId description]
     * @param   [string|null]  $default  [$default description]
     *
     * @return  [type]            [return description]
     */
    function au_config($key = null, $partnerId = null, $default = null)
    {
        $partnerId = ($partnerId === null) ? config('app.partnerId') : $partnerId;
        //Update config
        if (is_array($key)) {
            if (count($key) == 1) {
                foreach ($key as $k => $v) {
                    return AdminConfig::where('partner_id', $partnerId)
                        ->where('key', $k)
                        ->update(['value' => $v]);
                }
            } else {
                return false;
            }
        }
        //End update

        $allConfig = AdminConfig::getAllConfigOfPartner($partnerId);

        if ($key === null) {
            return $allConfig;
        }
        return array_key_exists($key, $allConfig) ? $allConfig[$key] :
            (array_key_exists($key, au_config_global()) ? au_config_global()[$key] : $default);
    }
}



if (!function_exists('au_config_admin') && !in_array('au_config_admin', config('helper_except', []))) {
    /**
     * Get config value in adin with session partner id
     * Default value is only used if the config key does not exist (including null values)
     *
     * @param   [type]$key  [$key description]
     * @param   null        [ description]
     *
     * @return  [type]      [return description]
     */
    function au_config_admin($key = null, $default = null)
    {
        return au_config($key, session('adminPartnerId'), $default);
    }
}


if (!function_exists('au_config_partner') && !in_array('au_config_partner', config('helper_except', []))) {
    /**
     * Get config value in partner with session partner id
     * Default value is only used if the config key does not exist (including null values)
     *
     * @param   [type]$key  [$key description]
     * @param   null        [ description]
     *
     * @return  [type]      [return description]
     */
    function au_config_partner($key = null, $default = null)
    {
        return au_config($key, session('partnerId'), $default);
    }
}


if (!function_exists('au_config_global') && !in_array('au_config_global', config('helper_except', []))) {
    /**
     * Get value config from table au_config for partner_id 0
     * Default value is only used if the config key does not exist (including null values)
     *
     * @param   [string|array] $key      [$key description]
     * @param   [string|null]  $default  [$default description]
     *
     * @return  [type]          [return description]
     */
    function au_config_global($key = null, $default = null)
    {
        //Update config
        if (is_array($key)) {
            if (count($key) == 1) {
                foreach ($key as $k => $v) {
                    return AdminConfig::where('partner_id', 0)
                        ->where('key', $k)
                        ->update(['value' => $v]);
                }
            } else {
                return false;
            }
        }
        //End update

        $allConfig = [];
        try {
            $allConfig = AdminConfig::getAllGlobal();
        } catch (\Throwable $e) {
            //
        }
        if ($key === null) {
            return $allConfig;
        }
        if (!array_key_exists($key, $allConfig)) {
            return $default;
        } else {
            return trim($allConfig[$key]);
        }
    }
}

if (!function_exists('au_config_group') && !in_array('au_config_group', config('helper_except', []))) {
    /*
    Group Config info
     */
    function au_config_group($group = null, $suffix = null)
    {
        $groupData = AdminConfig::getGroup($group, $suffix);
        return $groupData;
    }
}


if (!function_exists('au_partner') && !in_array('au_partner', config('helper_except', []))) {
    /**
     * Get info partner_id, table admin_partner
     *
     * @param   [string] $key      [$key description]
     * @param   [null|int]  $partner_id    partner id
     *
     * @return  [mix]
     */
    function au_partner($key = null, $partner_id = null, $default = null)
    {
        $partner_id = ($partner_id == null) ? config('app.partnerId') : $partner_id;

        //Update partner info
        if (is_array($key)) {
            if (count($key) == 1) {
                foreach ($key as $k => $v) {
                    return AdminPartner::where('id', $partner_id)->update([$k => $v]);
                }
            } else {
                return false;
            }
        }
        //End update

        $allPartnerInfo = [];
        try {
            $allPartnerInfo = AdminPartner::getListAll()[$partner_id]->toArray() ?? [];
        } catch (\Throwable $e) {
            //
        }

        $lang = app()->getLocale();
        $descriptions = $allPartnerInfo['descriptions'] ?? [];
        foreach ($descriptions as $row) {
            if ($lang == $row['lang']) {
                $allPartnerInfo += $row;
            }
        }
        if ($key == null) {
            return $allPartnerInfo;
        }
        return $allPartnerInfo[$key] ?? $default;
    }
}

if (!function_exists('au_partner_active') && !in_array('au_partner_active', config('helper_except', []))) {
    function au_partner_active($field = null)
    {
        switch ($field) {
            case 'code':
                return AdminPartner::getCodeActive();
                break;

            case 'domain':
                return AdminPartner::getPartnerActive();
                break;

            default:
                return AdminPartner::getListAllActive();
                break;
        }
    }
}


/*
Get all layouts
 */
if (!function_exists('au_partner_block') && !in_array('au_partner_block', config('helper_except', []))) {
    function au_partner_block()
    {
        return FegguPartnerBlockContent::getLayout();
    }
}

/**
 * Get css template
 */
if (!function_exists('au_partner_css')) {
    function au_partner_css()
    {
        $template = au_partner('template', config('app.partnerId'));
        if (\Schema::connection(AU_CONNECTION)->hasColumn((new FegguPartnerCss)->getTable(), 'template')) {
            $cssPartner =  FegguPartnerCss::where('partner_id', config('app.partnerId'))
            ->where('template', $template)->first();
        } else {
            $cssPartner =  FegguPartnerCss::where('partner_id', config('app.partnerId'))->first();
        }
        if ($cssPartner) {
            return $cssPartner->css;
        }
    }
}


/*
Get all block content
 */
if (!function_exists('au_link') && !in_array('au_link', config('helper_except', []))) {
    function au_link()
    {
        return FegguLink::getGroup();
    }
}
/*
Get all block content
 */
if (!function_exists('au_partner_info') && !in_array('au_partner_info', config('helper_except', []))) {
    function au_partner_info($key=null)
    {

           $info = AdminPartner::where('id',session('partnerId'))->firstOrFail();
           if (!$info){
               return;
           }
            return $info->$key??null;
    }
}

if (!function_exists('au_get_all_template') && !in_array('au_get_all_template', config('helper_except', []))) {
    /*
    Get all template
    */
    function au_get_all_template():array
    {
        $arrTemplates = [];
        foreach (glob(resource_path() . "/views/templates/*") as $template) {
            if (is_dir($template)) {
                $infoTemlate['code'] = explode('templates/', $template)[1];
                $config = ['name' => '', 'auth' => '', 'email' => '', 'website' => ''];
                if (file_exists($template . '/config.json')) {
                    $config = json_decode(file_get_contents($template . '/config.json'), true);
                }
                $infoTemlate['config'] = $config;
                $arrTemplates[$infoTemlate['code']] = $infoTemlate;
            }
        }
        return $arrTemplates;
    }
}


if (!function_exists('au_route') && !in_array('au_route', config('helper_except', []))) {
    /**
     * Render route
     *
     * @param   [string]  $name
     * @param   [array]  $param
     *
     * @return  [type]         [return description]
     */
    function au_route($name, $param = [])
    {
        if (!config('app.seoLang')) {
            $param = Arr::except($param, ['lang']);
        } else {
            $arrRouteExcludeLanguage = ['home','locale', 'currency', 'banner.click'];
            if (!key_exists('lang', $param) && !in_array($name, $arrRouteExcludeLanguage)) {
                $param['lang'] = app()->getLocale();
            }
        }
        if (Route::has($name)) {
            return route($name, $param);
        } else {
            return url('#'.$name);
        }
    }
}


if (!function_exists('au_route_admin') && !in_array('au_route_admin', config('helper_except', []))) {
    /**
     * Render route admin
     *
     * @param   [string]  $name
     * @param   [array]  $param
     *
     * @return  [type]         [return description]
     */
    function au_route_admin($name, $param = [])
    {
        if (Route::has($name)) {
            return route($name, $param);
        } else {
            return url('#'.$name);
        }
    }
}
if (!function_exists('au_route_partner') && !in_array('au_route_partner', config('helper_except', []))) {
    /**
     * Render route admin
     *
     * @param   [string]  $name
     * @param   [array]  $param
     *
     * @return  [type]         [return description]
     */
    function au_route_partner($name, $param = [])
    {
        if (Route::has($name)) {
            return route($name, $param);
        } else {
            return url('#'.$name);
        }
    }
}
