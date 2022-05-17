<?php
Route::group(['prefix' => 'partner_block'], function () {
    Route::get('/', 'AdminPartnerBlockController@index')->name('admin_partner_block.index');
    Route::get('create', 'AdminPartnerBlockController@create')->name('admin_partner_block.create');
    Route::post('/create', 'AdminPartnerBlockController@postCreate')->name('admin_partner_block.create');
    Route::get('/edit/{id}', 'AdminPartnerBlockController@edit')->name('admin_partner_block.edit');
    Route::post('/edit/{id}', 'AdminPartnerBlockController@postEdit')->name('admin_partner_block.edit');
    Route::get('/listblock', 'AdminPartnerBlockController@getListViewBlockHtml')->name('admin_partner_block.listblock');
    Route::post('/delete', 'AdminPartnerBlockController@deleteList')->name('admin_partner_block.delete');
});
