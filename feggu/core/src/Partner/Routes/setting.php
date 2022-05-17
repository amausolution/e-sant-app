<?php
        Route::group(['prefix' => 'info_settings'], function () {
        Route::get('/', 'SettingController@index')->name('setting.index');
        Route::post('/partner/{code}', 'SettingController@update')->name('setting.update');
        Route::post('/post_patient', 'SettingController@postSetting')->name('setting.consultation');
        Route::get('/show/{code}', 'SettingController@show')->name('setting.show');
        Route::post('/update', 'SettingController@update')->name('partner_config.update');
        Route::get('/update_global', 'SettingController@updateGlobal')->name('partner_config_global.update');
        });
Route::group(['prefix' => 'company'], function () {
    Route::get('/', 'CompanySettingController@company')->name('company.index');
    Route::post('/update', 'CompanySettingController@updateCompany')->name('company.update');
});
Route::group(['prefix' => 'email'], function () {
    Route::get('/', 'CompanySettingController@email')->name('email.index');
    Route::post('/update', 'CompanySettingController@updateEmail')->name('email.update');
});
Route::group(['prefix' => 'invoice'], function () {
    Route::get('/', 'CompanySettingController@company')->name('invoice.index');
    Route::post('/update', 'CompanySettingController@updateInvoice')->name('invoice.update');
});
Route::group(['prefix' => 'localization'], function () {
    Route::get('/', 'CompanySettingController@localization')->name('localization.index');
    Route::post('/update', 'CompanySettingController@updateLocalization')->name('localization.update');
});
Route::group(['prefix' => 'notifications'], function () {
    Route::get('/', 'CompanySettingController@notification')->name('notification.index');
    Route::post('/update', 'CompanySettingController@updateNotification')->name('notification.update');
});
Route::group(['prefix' => 'salary'], function () {
    Route::get('/', 'CompanySettingController@salary')->name('salary.index');
    Route::post('/update', 'CompanySettingController@updateSalary')->name('salary.update');
});
Route::group(['prefix' => 'role_permission'], function () {
    Route::get('/', 'CompanySettingController@role_permission')->name('roles_permission.index');
    Route::post('/update', 'CompanySettingController@updateRolePermission')->name('roles_permission.update');
});
Route::group(['prefix' => 'theme'], function () {
    Route::get('/', 'CompanySettingController@theme')->name('theme.index');
    Route::post('/update', 'CompanySettingController@updateTheme')->name('theme.update');
});
