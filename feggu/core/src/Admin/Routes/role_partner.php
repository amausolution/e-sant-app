<?php
    Route::group(['prefix' => 'partner_role'], function () {
        Route::get('/', 'RolePartnerController@index')->name('admin_role_partner.index');
        Route::get('create', 'RolePartnerController@create')->name('admin_role_partner.create');
        Route::post('/create', 'RolePartnerController@postCreate')->name('admin_role_partner.create');
        Route::get('/edit/{id}', 'RolePartnerController@edit')->name('admin_role_partner.edit');
        Route::post('/edit/{id}', 'RolePartnerController@postEdit')->name('admin_role_partner.edit');
        Route::post('/delete', 'RolePartnerController@deleteList')->name('admin_role_partner.delete');
    });
