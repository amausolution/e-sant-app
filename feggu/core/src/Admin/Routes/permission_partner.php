<?php
Route::group(['prefix' => 'permission_partner'], function () {
    Route::get('/', 'PermissionPartnerController@index')->name('admin_permission_partner.index');
    Route::get('create', 'PermissionPartnerController@create')->name('admin_permission_partner.create');
    Route::post('/create', 'PermissionPartnerController@postCreate')->name('admin_permission_partner.create');
    Route::get('/edit/{id}', 'PermissionPartnerController@edit')->name('admin_permission_partner.edit');
    Route::post('/edit/{id}', 'PermissionPartnerController@postEdit')->name('admin_permission_partner.edit');
    Route::post('/delete', 'PermissionPartnerController@deleteList')->name('admin_permission_partner.delete');
});
