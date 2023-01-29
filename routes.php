<?php

use Xpressengine\Plugins\Claim\Plugin;

// settings routes
Route::settings(Plugin::getId(), function () {
    Route::get(
        '/',
        [
            'as' => 'manage.claim.claim.index',
            'uses' => 'ManagerController@index',
            'settings_menu' => 'contents.claim'
        ]
    );
    Route::post('delete', ['as' => 'manage.claim.claim.delete', 'uses' => 'ManagerController@delete']);
    Route::get('config', ['as' => 'manage.claim.claim.config', 'uses' => 'ManagerController@config']);
    Route::get(
        'config/edit',
        ['as' => 'manage.claim.claim.config.edit', 'uses' => 'ManagerController@configEdit']
    );
    Route::post(
        'config/update',
        ['as' => 'manage.claim.claim.config.update', 'uses' => 'ManagerController@configUpdate']
    );
}, ['namespace' => 'Xpressengine\Plugins\Claim\Controllers']);


// fixed routes
Route::fixed('claim', function () {
    Route::get('', ['as' => 'fixed.claim.index', 'uses' => 'ClaimController@index']);
    Route::post('store', ['as' => 'fixed.claim.store', 'uses' => 'ClaimController@store']);
    Route::post('destroy', ['as' => 'fixed.claim.destroy', 'uses' => 'ClaimController@destroy']);
}, ['namespace' => 'Xpressengine\Plugins\Claim\Controllers']);
