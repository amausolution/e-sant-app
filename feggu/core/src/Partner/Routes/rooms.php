<?php

Route::group(['prefix'=>'rooms'],function (){
    Route::get('/','RoomsController@index')->name('room.index');
    Route::get('/create','RoomsController@create')->name('room.create');
    Route::post('/create','RoomsController@store')->name('room.store');
    Route::post('/delete/{id}','RoomsController@destroy')->name('room.destroy');
    Route::get('/edit/{id}','RoomsController@edit')->name('room.edit');
    Route::post('/edit/{id}','RoomsController@update')->name('room.update');
    Route::post('/add_bed','RoomsController@addBed')->name('room.add_bed');
});
