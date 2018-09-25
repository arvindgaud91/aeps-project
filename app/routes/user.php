<?php


Route::get('/sessions',  function (){ dd(Cookie::get('user')); });

Route::get('/admins', 'UsersController@getAdmins');
Route::get('/admins/add', 'UsersController@getAddAdmin');
Route::post('/users', 'UsersController@postAddAdmin');

Route::get('/users', 'UsersController@getUsers');
Route::post('/users/{userId}/update-status', 'UsersController@postUpdateStatus');
Route::post('/users/{userId}/kyc-details', 'UsersController@postKYCDetails');
Route::post('/users/{userId}', 'UsersController@postUpdateUserObj');
Route::get('/users/{userId}', 'UsersController@getUserDetails');

Route::get('/users/{userId}/profile', 'UsersController@getProfile');
Route::get('/users/{userId}/actions/change-password', 'UsersController@getChangePasswordPage');
Route::post('/api/v1/users/{userId}/actions/change-password', 'UsersController@postChangePassword');
Route::get('/users/{userId}/certificate', 'UsersController@getCertificate');

Route::get('/api/v1/users/{id}/actions/incoming-balance-requests', 'WalletsController@getIncomingBalanceRequestsByStatus');
Route::get('/users/actions/credit-request/vendor/{id}', 'WalletsController@getCreditWalletRequest');
Route::post('/api/v1/users/{id}/actions/credit-request', 'WalletsController@postCreditWalletRequest');

/**
 * Debit vendors Balance - by dist & super-dist
 *
 */
Route::get('/users/actions/debit-request/vendor/{id}', 'WalletsController@getDebitWalletRequest');
Route::post('/api/v1/users/{id}/actions/debit-wallet', 'UsersController@postDebitWallet');

Route::post('/users/{id}/actions/profile/update', 'UsersController@postProfile');

//playstore register
Route::post('/api/mobile/v1/createPlayStoreUsers', 'UsersController@createPlayStoreUsers');

//ChangePassword Api for DMT
Route::post('/api/v1/users/{userId}/actions/password-change-for-dmt','UsersController@postChangePasswordDMT');

//Reset Session
Route::get('/users/{userId}/reset-session','UsersController@getResetSession');
Route::post('/users/{userId}/reset-session','UsersController@postResetSession');\

//SMS 

Route::get('/sms_cron/user_balance/aeps','UsersController@smsUserBalance');
Route::get('/sms_cron/user_closing_balance/aeps','UsersController@smsUserClosingBalance');
Route::get('/sms_cron/commission_rate/aeps','UsersController@smsCommissionRate');
Route::get('/sms_cron/credit_commission/aeps','UsersController@smsCreditCommissions');
