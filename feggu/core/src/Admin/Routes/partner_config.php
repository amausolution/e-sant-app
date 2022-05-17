<?php
Route::group(['prefix' => 'feggu_config'], function () {
    Route::get('/', 'AdminPartnerConfigController@index')->name('admin_config.index');
    Route::post('/update', 'AdminPartnerConfigController@update')->name('admin_config.update');
});
