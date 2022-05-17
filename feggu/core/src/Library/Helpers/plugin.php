<?php

use App\Http\Controllers\RootPartnerController;

if (!function_exists('au_get_all_plugin') && !in_array('au_get_all_plugin', config('helper_except', []))) {
    /**
     * Get all class plugin
     *
     * @param   [string]  $code  Payment, Shipping
     *
     * @return  [array]
     */
    function au_get_all_plugin(string $code)
    {
        $code = au_word_format_class($code);
        $arrClass = [];
        $dirs = array_filter(glob(app_path() . '/Plugins/' . $code . '/*'), 'is_dir');
        if ($dirs) {
            foreach ($dirs as $dir) {
                $tmp = explode('/', $dir);
                $nameSpace = '\App\Plugins\\' . $code . '\\' . end($tmp);
                $nameSpaceConfig = $nameSpace . '\\AppConfig';
                if (file_exists($dir . '/AppConfig.php') && class_exists($nameSpaceConfig)) {
                    $arrClass[end($tmp)] = $nameSpace;
                }
            }
        }
        return $arrClass;
    }
}

if (!function_exists('au_get_plugin_installed') && !in_array('au_get_plugin_installed', config('helper_except', []))) {
    /**
     * Get all class plugin
     *
     * @param   [string]  $code  Payment, Shipping
     *
     */
    function au_get_plugin_installed($code = null, $onlyActive = true)
    {
        return \Feggu\Core\Admin\Models\AdminConfig::getPluginCode($code, $onlyActive);
    }
}




if (!function_exists('au_get_all_plugin_actived') && !in_array('au_get_all_plugin_actived', config('helper_except', []))) {
    /**
     * Get all class plugin actived
     *
     * @param   [string]  $code  Payment, Shipping
     *
     * @return  [array]
     */
    function au_get_all_plugin_actived(string $code)
    {
        $code = au_word_format_class($code);

        $pluginsActived = [];
        $allPlugins = au_get_all_plugin($code);
        if (count($allPlugins)) {
            foreach ($allPlugins as $keyPlugin => $plugin) {
                if (au_config($keyPlugin) && au_config($keyPlugin)['value'] == 1) {
                    $pluginsActived[$keyPlugin] = $plugin;
                }
            }
        }
        return $pluginsActived;
    }
}


    /**
     * Get namespace plugin controller
     *
     * @param   [string]  $code  Shipping, Payment,..
     * @param   [string]  $key  Paypal,..
     *
     * @return  [array]
     */

    if (!function_exists('au_get_class_plugin_controller') && !in_array('au_get_class_plugin_controller', config('helper_except', []))) {
        function au_get_class_plugin_controller(string $code, string $key = null)
        {
            if ($key == null) {
                return null;
            }

            $code = au_word_format_class($code);
            $key = au_word_format_class($key);

            $nameSpace = au_get_plugin_namespace($code, $key);
            $nameSpace = $nameSpace . '\Controllers\RootPartnerController';

            return $nameSpace;
        }
    }


    /**
     * Get namespace plugin config
     *
     * @param   [string]  $code  Shipping, Payment,..
     * @param   [string]  $key  Paypal,..
     *
     * @return  [array]
     */
    if (!function_exists('au_get_class_plugin_config') && !in_array('au_get_class_plugin_config', config('helper_except', []))) {
        function au_get_class_plugin_config(string $code, string $key)
        {
            $code = au_word_format_class($code);
            $key = au_word_format_class($key);

            $nameSpace = au_get_plugin_namespace($code, $key);
            $nameSpace = $nameSpace . '\AppConfig';

            return $nameSpace;
        }
    }

    /**
     * Get namespace module
     *
     * @param   [string]  $code  Block, Cms, Payment, shipping..
     * @param   [string]  $key  Content,Paypal, Cash..
     *
     * @return  [array]
     */
    if (!function_exists('au_get_plugin_namespace') && !in_array('au_get_plugin_namespace', config('helper_except', []))) {
        function au_get_plugin_namespace(string $code, string $key)
        {
            $code = au_word_format_class($code);
            $key = au_word_format_class($key);
            $nameSpace = '\App\Plugins\\'.$code.'\\' . $key;
            return $nameSpace;
        }
    }
