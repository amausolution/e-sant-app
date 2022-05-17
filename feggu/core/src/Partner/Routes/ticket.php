<?php
Route::group(['prefix' => 'cash_desk'],  function () {
   // Route::get('/', 'TicketController@index')->name('ticket.index');
    Route::get('create', 'TicketController@create')->name('partner_patient.create');
    Route::post('/create', 'TicketController@store')->name('consultation_patient.create');
});

//Route::get('/patient_new', 'TicketController@new_patient')->name('create.new');
Route::get('/patient_matricule', 'TicketController@return_patient')->name('create.matricule');
//Route::post('/ticket/create', 'TicketController@storeTicket')->name('store.ticket');
Route::post('/ticket/create', 'TicketController@store')->name('ticket.store');



