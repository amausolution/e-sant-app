<?php
    Route::group(['prefix' => 'patient'], function () {
        Route::get('/', 'PatientController@index')->name('partner_patient.index');
        Route::get('create', 'PatientController@create')->name('partner_patient.create');
        Route::post('/create', 'PatientController@store')->name('partner_patient.create');
        Route::get('/edit/{id}', 'PatientController@edit')->name('partner_patient.edit');
        Route::post('/edit/{id}', 'PatientController@update')->name('partner_patient.edit');
        Route::post('/delete', 'PatientController@delete')->name('partner_patient.delete');
        Route::get('/show/{id}', 'PatientController@show')->name('partner_patient.show');
//        Route::get('/consultation/{id}', 'PatientController@consultation')->name('partner_patient.consultation');
//        Route::post('/consultation/edit/{id}', 'PatientController@postConsultation')->name('partner_patient.postConsultation');
//        Route::get('/consultation/show/{id}', 'PatientController@showConsultation')->name('partner_patient.show.consultation');
    });
