<?php



 Route::get('/dmt-transfer-request/{id}', 'DmtTransferController@getDmtTransferRequest');


Route::post('/dmt-transfer-request', 'DmtTransferController@postDmtTransferRequest');
//Route::get('/dmt-transfer-report', 'DmtTransferController@getReport');
Route::get('/dmt-transfer-report', 'DmtTransferController@getReport');
Route::post('/export-dmt-report', 'DmtTransferController@getExportDmtTransferReport');

