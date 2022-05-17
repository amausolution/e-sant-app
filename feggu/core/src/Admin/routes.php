<?php

use Illuminate\Support\Facades\Route;
/*Route::group([
    'prefix' => AU_ADMIN_PREFIX.'/category_partner',
    'middleware' => AU_ADMIN_MIDDLEWARE,
    ], function () {
    Route::get('/', 'AdminCategoryPartnerController@index')->name('admin_category_partner.index');
    Route::post('/create', 'AdminCategoryPartnerController@store')->name('admin_category_partner.index');
    Route::get('/edit/{id}', 'AdminCategoryPartnerController@edite')->name('admin_category_partner.index');
    Route::post('/edit/{id}', 'AdminCategoryPartnerController@update')->name('admin_category_partner.index');
    Route::post('/delete', 'AdminCategoryPartnerController@deleteList')->name('admin_category_partner.delete');
});*/
//Route plugin
Route::group(
    [
        'middleware' => AU_ADMIN_MIDDLEWARE,
    ],
    function () {
        foreach (glob(app_path() . '/Plugins/*/*/Route.php') as $filename) {
            require_once $filename;
        }
        //Include route custom
        if (file_exists(base_path('routes/myroute.php'))) {
            require_once base_path('routes/myroute.php');
        }
    }
);

Route::group(
    [
        'prefix' => AU_ADMIN_PREFIX,
        'middleware' => AU_ADMIN_MIDDLEWARE,
        'namespace' => 'App\Admin\Controllers'
    ],
    function () {
        foreach (glob(__DIR__ . '/Routes/*.php') as $filename) {
            require_once $filename;
        }
        Route::get('/', 'DashboardController@index')->name('admin.home');
        Route::get('deny', 'DashboardController@deny')->name('admin.deny');
        Route::get('data_not_found', 'DashboardController@dataNotFound')->name('admin.data_not_found');
        Route::get('deny_single', 'DashboardController@denySingle')->name('admin.deny_single');

        //Language
        Route::get('locale/{code}', function ($code) {
            session(['locale' => $code]);
            return back();
        })->name('admin.locale');

        //theme
        Route::get('theme/{theme}', function ($theme) {
            session(['adminTheme' => $theme]);
            if (!\Admin::user()->isViewAll()) {
                \Admin::user()->update(['theme' => $theme]);
            }
            return back();
        })->name('admin.theme');
    }
);
