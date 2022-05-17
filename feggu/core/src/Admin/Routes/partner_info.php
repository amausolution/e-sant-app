<?php
Route::group(['prefix' => 'feggu_info'], function () {
    Route::get('/', 'AdminPartnerInfoController@index')->name('admin_partner.index');
    Route::post('/update_info', 'AdminPartnerInfoController@updateInfo')->name('admin_partner.update');
});
