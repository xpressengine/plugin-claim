<?php

use Xpressengine\Plugins\Claim\Plugin;

// settings routes
Route::settings(Plugin::getId(), function () {
    Route::get('/', [
        'as' => 'settings.claim.index',
        'uses' => 'ClaimSettingsController@index',
        'settings_menu' => 'contents.claim'
    ]);

    Route::get('/{id}', [
        'as' => 'settings.claim.edit',
        'uses' => 'ClaimSettingsController@edit'
    ]);

    Route::put('/{id}', [
        'as' => 'settings.claim.update',
        'uses' => 'ClaimSettingsController@update'
    ]);

    Route::post('/{id}/delete', [
        'as' => 'settings.claim.delete',
        'uses' => 'ClaimSettingsController@delete'
    ]);

    Route::get('config', [
        'as' => 'settings.claim.config',
        'uses' => 'ManagerController@config'
    ]);

    Route::get('config/edit', [
        'as' => 'settings.claim.config.edit',
        'uses' => 'ManagerController@configEdit'
    ]);

    Route::post('config/update', [
        'as' => 'settings.claim.config.update',
        'uses' => 'ManagerController@configUpdate'
    ]);

}, ['namespace' => 'Xpressengine\Plugins\Claim\Controllers']);


// fixed routes
Route::fixed('claim', function () {
    Route::get('', ['as' => 'fixed.claim.index', 'uses' => 'ClaimController@index']);
    Route::post('store', ['as' => 'fixed.claim.store', 'uses' => 'ClaimController@store']);
    Route::post('destroy', ['as' => 'fixed.claim.destroy', 'uses' => 'ClaimController@destroy']);
}, ['namespace' => 'Xpressengine\Plugins\Claim\Controllers']);
