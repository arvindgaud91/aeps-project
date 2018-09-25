<?php


Route::get('/settlement', 'SettlementController@getSettlement');
Route::post('/post-settlement', 'SettlementController@postSettlement');
Route::post('/post-settlement-request', 'SettlementController@postSettlementRequest');
Route::get('/settlement-report', 'SettlementController@getSettlementReport');
Route::get('/settlement-request', 'SettlementController@getSettlementRequest');
Route::post('/settlement-request', 'SettlementController@postSettlementRequest');

Route::post('/auto-settlement-request', 'SettlementController@autoSettlementRequest');

