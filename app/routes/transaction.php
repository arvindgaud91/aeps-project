<?php

// Dummy route
Route::get('/transactions/transact', 'TransactionsController@getTransactionForm');
Route::get('/transactions/{id}/actions/receipt', 'TransactionsController@getTransactionReceipt');

Route::post('/api/v1/transactions/balance-enquiry', 'TransactionsController@postBalanceEnquiry');
Route::post('/api/v1/transactions/deposit', 'TransactionsController@postDeposit');
Route::post('/api/v1/transactions/withdraw', 'TransactionsController@postWithdraw');

Route::get('/api/v1/transactions/{id}/actions/status', 'TransactionsController@getPollTransaction');
Route::post('/export-transactions-report', 'TransactionsController@export');

Route::post('/refund/otp/{transaction_id}', 'TransactionsController@sendRefundOTP');
Route::get('/refund/otp/{transaction_id}/form', 'TransactionsController@getRefundOTPForm');
Route::post('/refund/otp/{transaction_id}/process', 'TransactionsController@postRefundOTP');