<?php

use Feggu\Core\Partner\Models\PartnerConfig;

if (!function_exists('au_config_partner') && !in_array('au_config_partner', config('helper_except', []), true)) {
    /**
     * Get config value in adin with session partner id
     * Default value is only used if the config key does not exist (including null values)
     *
     * @param   [type]$key  [$key description]
     * @param   null        [ description]
     *
     * @return  [type]      [return description]
     */
    function au_config_partner($key = null, $default = null)
    {
        return au_partner_config($key, session('partnerId'), $default);
    }
}
if (!function_exists('au_partner_config') && !in_array('au_partner_config', config('helper_except', []), true)) {
    function au_partner_config($key = null, $partnerId = null, $default = null)
    {
        $partnerId = ($partnerId === null) ? session('partnerId') : $partnerId;
        //Update config
        if (is_array($key)) {
            if (count($key) == 1) {
                foreach ($key as $k => $v) {
                    return PartnerConfig::where('partner_id', $partnerId)
                        ->where('key', $k)
                        ->update(['value' => $v]);
                }
            } else {
                return false;
            }
        }
        //End update

        $allConfig = PartnerConfig::getAllConfigOfPartner($partnerId);

        if ($key === null) {
            return $allConfig;
        }
        return array_key_exists($key, $allConfig) ? $allConfig[$key] :
            (array_key_exists($key, au_partner_config_global()) ? au_partner_config_global()[$key] : $default);
    }
}
if (!function_exists('au_partner_config_global') && !in_array('au_partner_config_global', config('helper_except', []), true)) {
    /**
     * Get value config from table au_partner_config for partner_id 0
     * Default value is only used if the config key does not exist (including null values)
     *
     * @param   [string|array] $key      [$key description]
     * @param   [string|null]  $default  [$default description]
     *
     * @return  [type]          [return description]
     */
    function au_partner_config_global($key = null, $default = null)
    {
        //Update config
        if (is_array($key)) {
            if (count($key) == 1) {
                foreach ($key as $k => $v) {
                    return PartnerConfig::where('partner_id', 0)
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
            $allConfig = PartnerConfig::getAllGlobal();
        } catch (\Throwable $e) {
            //
        }
        if ($key === null) {
            return $allConfig;
        }
        if (!array_key_exists($key, $allConfig)) {
            return $default;
        }

        return trim($allConfig[$key]);
    }
}
if (!function_exists('au_partner_config_group') && !in_array('au_partner_config_group', config('helper_except', []), true)) {
    /*
    Group Config info
     */
    function au_partner_config_group($group = null, $suffix = null)
    {
        return PartnerConfig::getGroup($group, $suffix);
    }
}


