<?php
Route::group(['prefix' => 'partner_css'], function () {
    Route::get('/', 'AdminPartnerCssController@index')->name('admin_partner_css.index');
    Route::post('/', 'AdminPartnerCssController@postEdit');
});
