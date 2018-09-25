<?php
// User APIs
Route::post('/api/mobile/v1/login/agent', 'MobileApiUserController@postLoginAgent');
Route::post('/api/mobile/v1/test/login/agent', 'MobileApiUserController@postLoginAgentV2');
Route::post('/api/mobile/v1/login/distributor', 'MobileApiUserController@postLoginDistributor');
Route::post('/api/mobile/v1/login/superdistributor', 'MobileApiUserController@postLoginDistributor');
Route::post('/api/mobile/v1/user/forgotpassword', 'MobileApiUserController@postForgotPassword');
Route::post('/api/mobile/v1/user/forgotpassword/newpassword', 'MobileApiUserController@postForgotPasswordNewPassword');
Route::post('/api/mobile/v1/user/forgotpassword/resendotp', 'MobileApiUserController@postResendOTP');
Route::get('/api/mobile/v1/profile', 'MobileApiUserController@getProfile');
Route::post('/api/mobile/v1/user/changepassword', 'MobileApiUserController@postChangePassword');
Route::post('/api/mobile/v1/user/reset-session/request', 'MobileApiUserController@postRequestResetSession');
Route::post('/api/mobile/v1/user/reset-session', 'MobileApiUserController@postResetSession');
Route::post('/api/mobile/v1/user/reset-session/resend-otp', 'MobileApiUserController@postResendResetSessionOTP');

Route::get('/api/mobile/v1/user/logout', 'MobileApiUserController@getLogout');

// Agent V1 APIs
Route::get('/api/mobile/v1/agent/dashboard', 'MobileApiController@getAgentDashboard');
Route::get('/api/mobile/v1/agent/transaction', 'MobileApiController@getTransactions');
Route::get('/api/mobile/v1/banks', 'MobileApiController@getBanks');
Route::post('/api/mobile/v1/balanceinquiry/generatetransaction', 'MobileApiController@postGenerateBalanceEnquiry');
Route::post('/api/mobile/v1/balanceinquiry/confirmtransaction', 'MobileApiController@postConfirmTransaction');
Route::post('/api/mobile/v1/withdraw/generatetransaction', 'MobileApiController@postGenerateWithdraw');
Route::post('/api/mobile/v1/withdraw/confirmtransaction', 'MobileApiController@postConfirmTransaction');
Route::post('/api/mobile/v1/deposit/generatetransaction', 'MobileApiController@postGenerateDeposit');
Route::post('/api/mobile/v1/deposit/confirmtransaction', 'MobileApiController@postConfirmTransaction');
Route::post('/api/mobile/v1/transaction/status', 'MobileApiController@postTransactionStatus');
Route::get('/api/mobile/v1/modeoftransfer', 'MobileApiController@getModeOfTransfer');
Route::post('/api/mobile/v1/agent/wallet/creditrequest', 'MobileApiController@postBalanceRequest');
Route::get('/api/mobile/v1/bankaccountinfo', 'MobileApiController@getBankAccountInfo');
Route::get('/api/mobile/v1/agent/wallet/transaction', 'MobileApiController@getWalletReports');
Route::get('/api/mobile/v1/agent/creditrequests', 'MobileApiController@getBalanceRequests');
Route::post('/api/mobile/v1/agent/transactionDetails', 'MobileApiController@postTransactionDetails');
Route::get('/api/mobile/v1/agent/validateTerminalID', 'MobileApiController@getValidateTerminalId');

Route::get('/api/mobile/v1/agent/validateDCcode', 'MobileApiController@getValidateDCcode');

// Distributor APIs
Route::get('/api/mobile/v1/distributor/dashboard', 'MobileApiController@getDistributorDashboard');
Route::get('/api/mobile/v1/distributor/agent', 'MobileApiController@getAgents');
Route::post('/api/mobile/v1/distributor/creditwallet', 'MobileApiController@postCreditAgentWallet');
Route::post('/api/mobile/v1/distributor/debitwallet', 'MobileApiController@postDebitAgentWallet');
Route::get('/api/mobile/v1/distributor/wallet/transaction', 'MobileApiController@getWalletReportsForDistributor');
Route::get('/api/mobile/v1/distributor/transaction', 'MobileApiController@getTransactionsByDistributor');
Route::get('/api/mobile/v1/distributor/creditrequests', 'MobileApiController@getBalanceRequestsByDistributor');
Route::post('/api/mobile/v1/distributor/creditrequest', 'MobileApiController@postBalanceRequestByDistributor');
Route::get('/api/mobile/v1/contactus', 'MobileApiController@getContactDetails');

//Test APIs
Route::get('/api/mobile/v1/test/agent/dashboard', 'MobileApiController@getTestAgentDashboard');


Route::get('/api/mobile/v1/agent/devices', 'MobileApiController@getrddeviceinfo');



//Settlement api
Route::get('/api/mobile/v1/settlement/status', 'MobileApiController@getSettlementStatus');
Route::get('/api/mobile/v1/settlement', 'MobileApiController@getSettlement');
Route::post('/api/mobile/v1/settlement/request', 'MobileApiController@postSettlementRequest');
Route::get('/api/mobile/v1/settlement/account', 'MobileApiController@getSettlementAccount');
Route::post('/api/mobile/v1/settlement/details', 'MobileApiController@postSettlementDetails');

//device detail api
Route::post('/api/mobile/v1/device/details', 'MobileApiController@postDeviceDetails');


?>
