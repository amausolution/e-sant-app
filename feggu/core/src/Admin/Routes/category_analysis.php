<?php
Route::group(['prefix' => 'category_analysis'], function () {
    Route::get('/', 'CategoryAnalysisController@index')->name('admin_category_analysis.index');
    Route::get('create', 'CategoryAnalysisController@create')->name('admin_category_analysis.create');
    Route::post('/create', 'CategoryAnalysisController@store')->name('admin_category_analysis.store');
    Route::get('/edit/{id}', 'CategoryAnalysisController@edit')->name('admin_category_analysis.edit')->where('id', '[0-9]+');
    Route::get('/show/{id}', 'CategoryAnalysisController@show')->name('admin_category_analysis.show')->where('id', '[0-9]+');
    Route::post('/edit/{id}', 'CategoryAnalysisController@update')->name('admin_category_analysis.update')->where('id', '[0-9]+');
    Route::post('/delete', 'CategoryAnalysisController@deleteList')->name('admin_category_analysis.delete');
    Route::post('/specification/create', 'CategoryAnalysisController@add')->name('admin_category_analysis.add')->where('id', '[0-9]+');
});
