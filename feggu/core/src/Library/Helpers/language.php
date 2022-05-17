<?php

use Feggu\Core\Partner\Models\FegguLanguage;
use Illuminate\Support\Str;

if (!function_exists('au_language_all') && !in_array('au_language_all', config('helper_except', []))) {
    //Get all language
    function au_language_all()
    {
        return FegguLanguage::getListActive();
    }
}

if (!function_exists('au_languages') && !in_array('au_languages', config('helper_except', []))) {
    /*
    Render language
    WARNING: Dont call this function (or functions that call it) in __construct or midleware, it may cause the display language to be incorrect
     */
    function au_languages($locale)
    {
        $languages = \Feggu\Core\Partner\Models\Languages::getListAll($locale);
        return $languages;
    }
}

if (!function_exists('au_language_replace') && !in_array('au_language_replace', config('helper_except', []))) {
    /*
    Replace language
     */
    function au_language_replace(string $line, array $replace)
    {
        foreach ($replace as $key => $value) {
            $line = str_replace(
                [':'.$key, ':'.Str::upper($key), ':'.Str::ucfirst($key)],
                [$value, Str::upper($value), Str::ucfirst($value)],
                $line
            );
        }
        return $line;
    }
}


if (!function_exists('au_language_render') && !in_array('au_language_render', config('helper_except', []))) {
    /*
    Render language
    WARNING: Dont call this function (or functions that call it) in __construct or midleware, it may cause the display language to be incorrect
     */
    function au_language_render($string, array $replace = [], $locale = null)
    {
        $locale = $locale ? $locale : au_get_locale();
        $languages = au_languages($locale);
        return !empty($languages[$string]) ? au_language_replace($languages[$string], $replace): trans($string, $replace);
    }
}


if (!function_exists('au_language_quickly') && !in_array('au_language_quickly', config('helper_except', []))) {
    /*
    Language quickly
     */
    function au_language_quickly($string, $default = null)
    {
        $locale = au_get_locale();
        $languages = au_languages($locale);
        return !empty($languages[$string]) ? $languages[$string] : (\Lang::has($string) ? trans($string) : $default);
    }
}

if (!function_exists('au_get_locale') && !in_array('au_get_locale', config('helper_except', []))) {
    /*
    Get locale
    */
    function au_get_locale()
    {
        return app()->getLocale();
    }
}


if (!function_exists('au_lang_switch') && !in_array('au_lang_switch', config('helper_except', []))) {
    /**
     * Switch language
     *
     * @param   [string]  $lang
     *
     * @return  [mix]
     */
    function au_lang_switch($lang = null)
    {
        if (!$lang) {
            return ;
        }

        $languages = au_language_all()->keys()->all();
        if (in_array($lang, $languages)) {
            app()->setLocale($lang);
            session(['locale' => $lang]);
        } else {
            return abort(404);
        }
    }
}
