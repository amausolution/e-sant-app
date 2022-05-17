<?php
Route::group(['prefix' => 'partner_permission'], function () {
    Route::get('/', 'Auth\PartnerPermissionController@index')->name('partner_permission.index');
    Route::get('create', 'Auth\PartnerPermissionController@create')->name('partner_permission.create');
    Route::post('/create', 'Auth\PartnerPermissionController@postCreate')->name('partner_permission.create');
    Route::get('/edit/{id}', 'Auth\PartnerPermissionController@edit')->name('partner_permission.edit');
    Route::post('/edit/{id}', 'Auth\PartnerPermissionController@postEdit')->name('partner_permission.edit');
    Route::post('/delete', 'Auth\PartnerPermissionController@deleteList')->name('partner_permission.delete');
});
