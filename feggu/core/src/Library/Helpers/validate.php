<?php

if (!function_exists('au_check_view') && !in_array('au_check_view', config('helper_except', []))) {
    /**
     * Check view exist
     *
     * @param   [string]  $view path view
     *
     * @return  [string]         [$domain]
     */
    function au_check_view($view)
    {
        if (!view()->exists($view)) {
            au_report('View not found '.$view);
            echo  au_language_render('partner.view_not_exist', ['view' => $view]);
            exit();
        }
    }
}


if (!function_exists('au_clean') && !in_array('au_clean', config('helper_except', []), true)) {
    /**
     * Clear data
     * @param null $data
     * @param array $exclude
     * @param null $level_hight
     * @return array|string|string[]|null
     */
    function au_clean($data = null, $exclude = [], $level_hight = null)
    {
        if ($level_hight) {
            if (is_array($data)) {
                $data = array_map(static function ($v) {
                    return strip_tags($v);
                }, $data);
            } else {
                $data = strip_tags($data);
            }
        }
        if (is_array($data)) {
            array_walk($data, static function (&$v, $k) use ($exclude, $level_hight) {
                if (is_array($v)) {
                    $v = au_clean($v, $exclude, $level_hight);
                } else {
                    if ((is_array($exclude) && in_array($k, $exclude, true)) || (!is_array($exclude) && $k == $exclude)) {
                        $v = $v;
                    } else {
                        $v = htmlspecialchars_decode($v);
                        $v = htmlspecialchars($v, ENT_COMPAT, 'UTF-8');
                    }
                }
            });
        } else {
            $data = htmlspecialchars_decode($data);
            $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
        }
        return $data;
    }
}
