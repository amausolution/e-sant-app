<?php

if (!function_exists('au_captcha_method') && !in_array('au_captcha_method', config('helper_except', []))) {
    function au_captcha_method()
    {
        //If function captcha disable or dont setup
        if (empty(au_config('captcha_mode'))) {
            return null;
        }

        // If method captcha selected
        if (!empty(au_config('captcha_method'))) {
            $moduleClass = au_config('captcha_method');
            //If class plugin captcha exist
            if (class_exists($moduleClass)) {
                //Check plugin captcha disable
                $key = (new $moduleClass)->configKey;
                if (au_config($key)) {
                    return (new $moduleClass);
                } else {
                    return null;
                }
            }
        }
        return null;
    }
}

if (!function_exists('au_captcha_page') && !in_array('au_captcha_page', config('helper_except', []))) {
    function au_captcha_page()
    {
        if (empty(au_config('captcha_page'))) {
            return [];
        }

        if (!empty(au_config('captcha_page'))) {
            return json_decode(au_config('captcha_page'));
        }
    }
}

if (!function_exists('au_get_plugin_captcha_installed') && !in_array('au_get_plugin_captcha_installed', config('helper_except', []))) {
    /**
     * Get all class plugin captcha installed
     *
     * @param   [string]  $code  Payment, Shipping
     *
     */
    function au_get_plugin_captcha_installed($onlyActive = true)
    {
        $listPluginInstalled =  \Feggu\Core\Admin\Models\AdminConfig::getPluginCaptchaCode($onlyActive);
        $arrPlugin = [];
        if ($listPluginInstalled) {
            foreach ($listPluginInstalled as $key => $plugin) {
                $keyPlugin = au_word_format_class($plugin->key);
                $pathPlugin = app_path() . '/Plugins/Other/'.$keyPlugin;
                $nameSpaceConfig = '\App\Plugins\Other\\'.$keyPlugin.'\AppConfig';
                if (file_exists($pathPlugin . '/AppConfig.php') && class_exists($nameSpaceConfig)) {
                    $arrPlugin[$nameSpaceConfig] = au_language_render($plugin->detail);
                }
            }
        }
        return $arrPlugin;
    }
}
