<?php

use Illuminate\Support\Facades\Route;
if (config('feggu.application_mode', 1)) {
//Route api
Route::group(
    [
        'middleware' => AU_API_MIDDLEWARE,
        'prefix' => 'api',
        'namespace' => '\Feggu\Core\Api\Controllers',
    ],
    function () {

    //Customer
        Route::group(['prefix' => 'patient'], function () {
            Route::post('login', 'PatientAuthController@login');
            Route::post('create', 'PatientAuthController@create');
            Route::group([
            'middleware' => ['auth:api', 'scope:user, user-guest']
                ], function () {
                    Route::get('logout', 'PatientAuthController@logout');
                    Route::get('info', 'PatientController@getInfo');
                });
        });

        //Admin
        Route::group(['prefix' => 'admin'], function () {
            Route::post('login', 'AdminAuthController@login');
            Route::group([
          'middleware' => ['auth:admin-api', 'scope:admin-supper']
        ], function () {
            Route::get('logout', 'AdminAuthController@logout');
            Route::get('info', 'AdminController@getInfo');

            // Management customer
            Route::post('create_customer', 'AdminCustomerController@create');
            Route::get('customers', 'AdminCustomerController@customers');
            Route::get('customers/{id}', 'AdminCustomerController@customerDetail');


            Route::get('countries', 'AdminPartnerFront@allCountry');
            Route::get('countries/{id}', 'AdminPartnerFront@countryDetail');
            Route::get('currencies', 'AdminPartnerFront@allCurrency');
            Route::get('currencies/{id}', 'AdminPartnerFront@CurrencyDetail');
            Route::get('languages', 'AdminPartnerFront@allLanguage');
            Route::get('languages/{id}', 'AdminPartnerFront@LanguageDetail');


            Route::get('categories', 'AdminPartnerFront@allCategory');
            Route::get('categories/{id}', 'AdminPartnerFront@categoryDetail');
            Route::get('products', 'AdminPartnerFront@allProduct');
            Route::get('products/{id}', 'AdminPartnerFront@productDetail');
            Route::get('brands', 'AdminPartnerFront@allBrand');
            Route::get('brands/{id}', 'AdminPartnerFront@brandDetail');
            Route::get('supplieres', 'AdminPartnerFront@allSupplier');
            Route::get('supplieres/{id}', 'AdminPartnerFront@supplierDetail');
        });
        });
    }
);
}
