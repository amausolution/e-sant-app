<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

/*
String to Url
 */
if (!function_exists('au_word_format_url') && !in_array('au_word_format_url', config('helper_except', []))) {
    function au_word_format_url( $str)
    {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return strtolower(preg_replace(
            array('/[\s-]+|[-\s]+|[--]+/', '/^[-\s_]|[-_\s]$/'),
            array('-', ''),
            strtolower($str)
        ));
    }
}


if (!function_exists('au_url_render') && !in_array('au_url_render', config('helper_except', []))) {
    /*
    url render
     */
    function au_url_render(string $string):string
    {
        $arrCheckPartner = explode('partner::', $string);
        $arrCheckUrl = explode('admin::', $string);

        if (count($arrCheckPartner) == 2) {
            $string = Str::start($arrCheckPartner[1], '/');
            $string = AU_PARTNER_PREFIX . $string;
            return url($string);
        }

        if (count($arrCheckUrl) == 2) {
            $string = Str::start($arrCheckUrl[1], '/');
            $string = AU_ADMIN_PREFIX . $string;
            return url($string);
        }
        return url($string);
    }
}


if (!function_exists('au_html_render') && !in_array('au_html_render', config('helper_except', []))) {
    /*
    Html render
     */
    function au_html_render(string $string):string
    {
        $string = htmlspecialchars_decode($string);
        return $string;
    }
}

if (!function_exists('au_word_format_class') && !in_array('au_word_format_class', config('helper_except', []))) {
    /*
    Format class name
     */
    function au_word_format_class(string $word):string
    {
        $word = Str::camel($word);
        $word = ucfirst($word);
        return $word;
    }
}

if (!function_exists('au_word_limit') && !in_array('au_word_limit', config('helper_except', []))) {
    /*
    Truncates words
     */
    function au_word_limit(string $word, int $limit = 20, string $arg = ''):string
    {
        $word = Str::limit($word, $limit, $arg);
        return $word;
    }
}

if (!function_exists('au_token') && !in_array('au_token', config('helper_except', []))) {
    /*
    Create random token
     */
    function au_token(int $length = 32)
    {
        $token = Str::random($length);
        return $token;
    }
}

if (!function_exists('au_report') && !in_array('au_report', config('helper_except', []))) {
    /*
    Handle report
     */
    function au_report(string $msg, array $ext = [])
    {
        $msg = date('Y-m-d H:i:s').':'.PHP_EOL.$msg.PHP_EOL;
        if (!in_array('slack', $ext)) {
            if (config('logging.channels.slack.url')) {
                try {
                    \Log::channel('slack')->error($msg);
                } catch (\Throwable $e) {
                    $msg .= $e->getFile().'- Line: '.$e->getLine().PHP_EOL.$e->getMessage().PHP_EOL;
                }
            }
        }
        \Log::error($msg);
    }
}


if (!function_exists('au_push_include_view') && !in_array('au_push_include_view', config('helper_except', []))) {
    /**
     * Push view
     *
     * @param   [string]  $position
     * @param   [string]  $pathView
     *
     */
    function au_push_include_view(string $position, string $pathView)
    {
        $includePathView = config('au_include_view.'.$position, []);
        $includePathView[] = $pathView;
        config(['au_include_view.'.$position => $includePathView]);
    }
}


if (!function_exists('au_push_include_script') && !in_array('au_push_include_script', config('helper_except', []))) {
    /**
     * Push script
     *
     * @param   [string]  $position
     * @param   [string]  $pathScript
     *
     */
    function au_push_include_script($position, $pathScript)
    {
        $includePathScript = config('au_include_script.'.$position, []);
        $includePathScript[] = $pathScript;
        config(['au_include_script.'.$position => $includePathScript]);
    }
}


/**
 * convert datetime to date
 */
if (!function_exists('au_datetime_to_date') && !in_array('au_datetime_to_date', config('helper_except', []))) {
    function au_datetime_to_date($datetime, $format = 'Y-m-d')
    {
        if (empty($datetime)) {
            return null;
        }
        return  date($format, strtotime($datetime));
    }
}


if (!function_exists('admin') && !in_array('admin', config('helper_except', []))) {
    /**
     * Admin login information
     */
    function admin()
    {
        return auth()->guard('admin');
    }
}

if (!function_exists('partner') && !in_array('partner', config('helper_except', []))) {
    /**
     * partner login information
     */
    function partner()
    {
        return auth()->guard('partner');
    }
}
