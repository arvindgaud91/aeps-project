<?php

Route::get('/wallets/balance-request', 'WalletsController@getBalanceRequest');
Route::get('/wallets/balance-request/from-distributor', 'WalletsController@getBalanceRequestFromDistributor');
Route::get('/wallets/balance-request/from-super-distributor', 'WalletsController@getBalanceRequestFromSuperDistributor');

Route::get('/wallets/balance-request/incoming/vendor/{id}', 'WalletsController@getIncomingBalanceRequestByParentVendor');

Route::post('/api/v1/wallets/balance-requests/', 'WalletsController@postBalanceRequest');
Route::post('/api/v1/wallets/balance-requests/from-distributors', 'WalletsController@postBalanceRequestFromDistributors');
Route::post('/api/v1/wallets/balance-requests/from-super-distributors', 'WalletsController@postBalanceRequestFromSuperDistributors');

Route::post('/api/v1/wallets/balance-requests/{id}/from-super-distributors/actions/approve', 'WalletsController@postApproveBalanceRequestBySuperDistributor');
Route::post('/api/v1/wallets/balance-requests/{id}/from-distributors/actions/approve', 'WalletsController@postApproveBalanceRequestByDistributor');

Route::post('/api/v1/wallets/balance-requests/{id}/from-super-distributors/actions/reject', 'WalletsController@postRejectBalanceRequestBySuperDistributor');
Route::post('/api/v1/wallets/balance-requests/{id}/from-distributors/actions/reject', 'WalletsController@postRejectBalanceRequestByDistributor');
Route::post('/export','WalletsController@export');

Route::get('/aeps-to-dmt/{id}/transfer-request', 'WalletsController@getTransferRequest');
Route::get('/wallets/export-wallet-request-data/{id}', 'WalletsController@export_wallet_request_data');
