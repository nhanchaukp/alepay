<?php
App::booted(function() {
    $namespace = 'Nhanchaukp\Alepay\Http\Controllers';

    Route::namespace($namespace)->name('app.')->group(function() {
        Route::get('/demo-alepay','AlepayController@demoAlepay');
        Route::post('/thanh-toan-qua-alepay','AlepayController@alepaySetup')->name('alepay');
        Route::get('/ket-qua-alepay','AlepayController@alepayResult');
    });
});