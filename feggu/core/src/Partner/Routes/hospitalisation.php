<?php
   Route::group(['prefix' => 'hospitalisation'], function () {
        Route::get('/', 'HospitalisationController@index')->name('hospitalisation.index');
        Route::get('/patient', 'HospitalisationController@patient')->name('hospitalisation.patient');
        Route::get('create', 'HospitalisationController@create')->name('hospitalisation.create');
        Route::post('/create', 'HospitalisationController@store')->name('hospitalisation.create');
        Route::get('/edit/{slug}', 'HospitalisationController@edit')->name('hospitalisation.edit');
        Route::post('/edit/{slug}', 'HospitalisationController@update')->name('hospitalisation.update');
        Route::post('/delete', 'HospitalisationController@delete')->name('hospitalisation.delete');
        Route::get('/show/{slug}', 'HospitalisationController@show')->name('hospitalisation.show');
    });
Route::group(['prefix' => 'patient_hospitalisation'], function () {
    Route::get('/', 'HospitalisationController@patient')->name('hospitalisation.patient');
    Route::get('/show/{slug}', 'HospitalisationController@showHospitalized')->name('hospitalisation.show_hospitalized');
    Route::get('/{slug}', 'HospitalisationController@endHospitalized')->name('hospitalisation.end');
    Route::post('/done', 'HospitalisationController@getOut')->name('hospitalisation.get_out');
    Route::post('/post_diagnostic', 'HospitalisationController@diagnostic')->name('hospitalisation.postDiag');
    Route::post('/post_rapport', 'HospitalisationController@rapport')->name('hospitalisation.postRapport');
});
