<?php
/**
 * Route partner
 */
if(au_config('Plugin_Key')) {
Route::group(
    [
        'prefix'    => 'plugin/PluginUrlKey',
        'namespace' => 'App\Plugins\Plugin_Code\Plugin_Key\Controllers',
    ],
    function () {
        Route::get('index', 'FrontController@index')
        ->name('PluginUrlKey.index');
    }
);
}
/**
 * Route admin
 */
Route::group(
    [
        'prefix' => AU_ADMIN_PREFIX.'/PluginUrlKey',
        'middleware' => AU_ADMIN_MIDDLEWARE,
        'namespace' => 'App\Plugins\Plugin_Code\Plugin_Key\Admin',
    ],
    function () {
        Route::get('/', 'AdminController@index')
        ->name('admin_PluginUrlKey.index');
    }
);
