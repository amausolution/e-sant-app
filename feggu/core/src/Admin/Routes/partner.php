<?php
Route::group(['prefix' => 'partner'], function () {
    Route::get('/', 'AdminPartnerController@index')->name('admin_partner.index');
    Route::get('create', 'AdminPartnerController@create')->name('admin_partner.create');
    Route::post('/create', 'AdminPartnerController@postCreate')->name('admin_partner.create');
    Route::get('/edit/{id}', 'AdminPartnerController@edit')->name('admin_partner.edit');
    Route::post('/edit/{id}', 'AdminPartnerController@updateInfo')->name('admin_partner.edit');
    Route::post('/delete', 'AdminPartnerController@deleteList')->name('admin_partner.delete');
    Route::get('/{id}/detail', 'AdminPartnerController@show')->name('admin_partner.show');
    Route::get('/{id}/patients', 'AdminPartnerController@patient')->name('admin_partner.patient');
    Route::get('/{id}/doctors', 'AdminPartnerController@doctor')->name('admin_partner.doctor');
    Route::get('/{id}/employees', 'AdminPartnerController@employee')->name('admin_partner.employee');
});
