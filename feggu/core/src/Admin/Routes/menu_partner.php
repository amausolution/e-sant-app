<?php
Route::group(['prefix' => 'menu_partner'], function () {
    Route::get('/', 'AdminMenuPartnerController@index')->name('admin_menu_partner.index');
    Route::post('/create', 'AdminMenuPartnerController@postCreate')->name('admin_menu_partner.create');
    Route::get('/edit/{id}', 'AdminMenuPartnerController@edit')->name('admin_menu_partner.edit');
    Route::post('/edit/{id}', 'AdminMenuPartnerController@postEdit')->name('admin_menu_partner.edit');
    Route::post('/delete', 'AdminMenuPartnerController@deleteList')->name('admin_menu_partner.delete');
    Route::post('/update_sort', 'AdminMenuPartnerController@updateSort')->name('admin_menu_partner.update_sort');
});
