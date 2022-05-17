<?php
Route::group(['prefix' => 'category_partner'], function () {
    Route::get('/', 'AdminCategoryPartnerController@index')->name('admin_category_partner.index');
    Route::get('create', 'AdminCategoryPartnerController@create')->name('admin_category_partner.create');
    Route::post('/create', 'AdminCategoryPartnerController@store')->name('admin_category_partner.store');
    Route::get('/edit/{id}', 'AdminCategoryPartnerController@edit')->name('admin_category_partner.edit');
    Route::post('/edit/{id}', 'AdminCategoryPartnerController@update')->name('admin_category_partner.update');
    Route::post('/delete', 'AdminCategoryPartnerController@deleteList')->name('admin_category_partner.delete');
});
