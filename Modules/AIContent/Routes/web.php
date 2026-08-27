<?php

use Illuminate\Support\Facades\Route;

Route::prefix('ai-content')->middleware('auth')->group(function () {

    Route::get('settings', 'AISettingsController@index')->name('ai-content.settings');
    Route::post('settings', 'AISettingsController@store')->name('ai-content.update_settings');


    Route::post('generate-text', 'AIContentController@generate')->name('ai-content.generate_text');


    Route::get('content', 'AIContentController@index')->name('ai-content.content');
    Route::post('content/update', 'AIContentController@update')->name('ai-content.update');
    Route::get('delete/{id}', 'AIContentController@delete')->name('ai-content.delete');

});
