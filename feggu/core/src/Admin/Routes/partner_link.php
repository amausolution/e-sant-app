<?php
Route::group(['prefix' => 'partner_link'], function () {
    Route::get('/', 'AdminPartnerLinkController@index')->name('admin_partner_link.index');
    Route::get('create', 'AdminPartnerLinkController@create')->name('admin_partner_link.create');
    Route::post('/create', 'AdminPartnerLinkController@postCreate')->name('admin_partner_link.create');
    Route::get('/edit/{id}', 'AdminPartnerLinkController@edit')->name('admin_partner_link.edit');
    Route::post('/edit/{id}', 'AdminPartnerLinkController@postEdit')->name('admin_partner_link.edit');
    Route::post('/delete', 'AdminPartnerLinkController@deleteList')->name('admin_partner_link.delete');
});
