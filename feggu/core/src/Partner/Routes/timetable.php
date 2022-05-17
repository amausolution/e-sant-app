<?php
    Route::group(['prefix' => 'timetable'], function () {
        Route::get('/', 'TimetableController@index')->name('timetable.index');
        Route::get('create', 'TimetableController@create')->name('timetable.create');
        Route::post('/create', 'TimetableController@store')->name('timetable.store');
        Route::get('/edit/{id}', 'TimetableController@edit')->name('timetable.edit');
        Route::post('/edit/{id}', 'TimetableController@update')->name('timetable.edit');
        Route::post('/delete', 'TimetableController@delete')->name('timetable.delete');
        Route::get('/show/{id}', 'TimetableController@show')->name('timetable.show');
    });
