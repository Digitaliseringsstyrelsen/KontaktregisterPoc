<?php

Route::resource('contacts', '\Api\Controllers\ContactController', [
    'only' => [
        'show',
        'store',
    ],
]);

Route::get('contacts/{contact}/log', [
    'as' => 'contacts.log',
    'uses' => '\Api\Controllers\ContactController@log'
]);

Route::resource('contacts.subscriptions', '\Api\Controllers\SubscriptionController', [
    'only' => [
        'show',
        'index',
        'store',
        'update',
        'destroy',
    ],
]);

Route::post('contacts/{contact}/subscriptions/{subscription}/accept-terms', [
    'as' => 'contacts.subscriptions.accept-terms',
    'uses' => '\Api\Controllers\SubscriptionController@acceptTerms'
]);

Route::resource('contacts.medias', '\Api\Controllers\MediaController', [
    'only' => [
        'index',
        'store',
        'destroy',
        'update',
    ],
]);

Route::resource('contacts.media-subscriptions', '\Api\Controllers\MediaSubscriptionController', [
    'only' => [
        'store',
    ],
]);

Route::delete('contacts/{contact}/media-subscriptions', [
    'as'   => 'contacts.media-subscriptions.destroy',
    'uses' => '\Api\Controllers\MediaSubscriptionController@destroy',
]);

Route::resource('subscription-types', '\Api\Controllers\SubscriptionTypeController', [
    'only' => [
        'index',
    ],
]);
Route::resource('terms', '\Api\Controllers\TermController', [
    'only' => [
        'index',
        'show',
    ],
]);
Route::get('search', '\Api\Controllers\ContactController@search');
Route::post('register', '\Api\Controllers\ContactController@register');
