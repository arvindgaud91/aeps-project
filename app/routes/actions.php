<?php

Route::post('/api/v1/actions/password-reset-otp', 'ActionsController@postPasswordResetOTP');
Route::post('/api/v1/actions/new-password-otp', 'ActionsController@postNewPasswordOTP');
Route::post('/api/v1/actions/session-reset-otp', 'ActionsController@postSessionResetOTP');
Route::post('/api/v1/actions/new-session-otp', 'SessionsController@postNewSessionOTP');

Route::post('/api/v1/actions/reset-session/{phone_no}',
	'SessionsController@postResetSession');