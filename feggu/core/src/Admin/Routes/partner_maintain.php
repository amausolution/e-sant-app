<?php
Route::group(['prefix' => 'partner_maintain'], function () {
    Route::get('/', 'AdminPartnerMaintainController@index')->name('admin_partner_maintain.index');
    Route::post('/', 'AdminPartnerMaintainController@postEdit');
});
