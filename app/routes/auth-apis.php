<?php

Route::post('/api/auth/v1/user', 'AuthApiController@postSetUser');
Route::get('/api/auth/v1/user/{id}/parent', 'AuthApiController@getUserParent');
Route::get('/api/auth/v1/cities', 'AuthApiController@getCities');
Route::get('/api/auth/v1/users', 'AuthApiController@getUsers');
Route::get('/api/auth/v1/children', 'AuthApiController@getChildren');
Route::get('/api/auth/v1/child/{id}', 'AuthApiController@getChild');
Route::post('/api/auth/v1/users/actions/admin/verify', 'AuthApiController@postVerifyAdmin');
Route::post('/api/auth/v1/dmtchildren', 'AuthApiController@getDmtChildren');
Route::get('/api/v1/user-detail-for-dmtsales','AuthApiController@getUserDetails');
Route::get('/api/v1/user-detail-for-admin', 'AuthApiController@getUsersDetailsForAdmin');
?>