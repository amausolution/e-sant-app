<?php
    Route::group(['prefix' => 'role'], function () {
        Route::get('/', 'Auth\PartnerRoleController@index')->name('partner_role.index');
        Route::get('create', 'Auth\PartnerRoleController@create')->name('partner_role.create');
        Route::post('/create', 'Auth\PartnerRoleController@postCreate')->name('partner_role.create');
        Route::get('/edit/{id}', 'Auth\PartnerRoleController@edit')->name('partner_role.edit');
        Route::post('/edit/{id}', 'Auth\PartnerRoleController@postEdit')->name('partner_role.edit');
        Route::post('/delete', 'Auth\PartnerRoleController@deleteList')->name('partner_role.delete');
    });
