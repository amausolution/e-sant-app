<?php
Route::group(['prefix' => 'auth_partner'], function () {
    $authController = Auth\LoginPartnerController::class;
    Route::get('login', $authController . '@getLogin')->name('partner.login');
    Route::post('/login', $authController . '@postLogin')->name('partner.login');
    Route::get('logout', $authController . '@getLogout')->name('partner.logout');
    Route::get('setting', $authController . '@getSetting')->name('partner.setting');
    Route::post('setting', $authController . '@putSetting')->name('partner.setting');
    Route::get('profile', $authController . '@profile')->name('partner.profile');

});
