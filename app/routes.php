<?php
use Acme\Auth\Auth;
use Acme\Helper\Rabbit;
use Acme\Helper\GateKeeper;
use Acme\ISO8583\ISO8583;
use Carbon\Carbon;
Route::get('/update-waller-transactions','PagesController@update_waller_transactions');
Route::get('/withdraw', function () {
	if (! in_array(Input::get('type', ''), ['balance-enquiry', 'deposit', 'withdraw'])) return Redirect::to('/');
		if (Input::get('type') == 'balance-enquiry')
			GateKeeper::checkBlacklist(Auth::user(), 1, 'balance-enquiry');
			if (Input::get('type') == 'deposit')
				GateKeeper::checkBlacklist(Auth::user(), 1, 'deposit');
				if (Input::get('type') == 'withdraw')
					GateKeeper::checkBlacklist(Auth::user(), 1, 'withdraw');

					$data['transactionType'] = Input::get('type');
					return View::make('dummy.test1')->with($data);
				});
Route::get('/incoming-request', function () {
	return View::make('wallets.incoming-request');
});

Route::get('/rd-link', function () {
	return View::make('users.browser');
});


Route::get('/receipt', function () {
	return View::make('receipts.receipt');
});

Route::get('/services',function(){
	return View::make('sessions.services');
});

Route::get('/error', function () {
	return View::make('home.error');
});

Route::get('/top-bar', 'HomeController@getServicesTopBar');

Route::get('/transaction-reports', function () {
	$serviceDict = [
		0 => 'Balance request',
		1 => 'Deposit request',
		2 => 'Withdraw request',
		3 => 'To Pay request'
	];
	   $Bank=new Bank;
       $Bank->setConnection('mysql1');

        $AepsTransaction = new  AepsTransaction;
        $AepsTransaction->setConnection('mysql1');

       $WalletTransaction = new WalletTransaction;
       $WalletTransaction->setConnection('mysql1');

        $AepsWalletAction = new AepsWalletAction;
       $AepsWalletAction->setConnection('mysql1');
       

        

	$banks = $Bank->get();
	$banksDict = getBanksDict($banks);

	$queryObj = $AepsTransaction->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')
	->with('commission');
	if (Input::has('fromDate') && Input::has('toDate')) {
		$queryObj = $queryObj->where('created_at', '>=', Input::get('fromDate'));
		$queryObj = $queryObj->where('created_at', '<=', Input::get('toDate'));
	}

	$transactions =	$queryObj->paginate(50);
	$txs = array_map(function ($tx) use ($serviceDict, $banksDict)
	{   
		 
	   $Bank=new Bank;
       $Bank->setConnection('mysql1');

        $AepsTransaction = new  AepsTransaction;
        $AepsTransaction->setConnection('mysql1');

       $WalletTransaction = new WalletTransaction;
       $WalletTransaction->setConnection('mysql1');

        $AepsWalletAction = new AepsWalletAction;
       $AepsWalletAction->setConnection('mysql1');

		$wallet_balance = "NA";
		$aeps_wallet_action = $AepsWalletAction->where('transaction_id', $tx['id'])->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
		if ($aeps_wallet_action) {
			if ($aeps_wallet_action->credit_id > 0) {
				$wallet_balance = $WalletTransaction->find($aeps_wallet_action->credit_id)->balance;
			}
			if ($aeps_wallet_action->debit_id > 0) {
				$wallet_balance = $WalletTransaction->find($aeps_wallet_action->debit_id)->balance;
			}
		}
		$commissionObj = $AepsWalletAction->where('transaction_id', $tx['id'])->where('user_id', Auth::user()->id)->where('commission', 1)->first();
		$commission = $commissionObj ? $commissionObj->amount : 0;
		return [
			'tx_date' => $tx['created_at'],
			'tx_id' => $tx['id'],
			'service' => $serviceDict[$tx['type']],
			'aadhar_no' => $tx['aadhar_no'],
			'bank_name' => $banksDict[$tx['bank_id']],
			'rrn_no' => $tx['rrn'],
			'amount' => $tx['amount'],
			'commission_amount' => $commission,
			'balance' => $tx['balance'],
			'refund_status' => $tx['refund_status'],
			'status' => getStatus($tx['status'], $tx['result']),
			// @todo add a valid computer generated remark based on transaction result
			'remarks' => '',
			'wallet_balance' => $wallet_balance,
			'receipt_link' => '/transactions/'.$tx['id'].'/actions/receipt'
		];
	}, $transactions->getItems());
    
        $bal_enq_unsuccess = AepsTransaction::where('user_id',Auth::user()->id)->where('type', 0)->where('result', 0)->whereRaw('date(created_at) = ?', [Carbon::today()])->count();
    
        $bal_enq_success = AepsTransaction::where('user_id',Auth::user()->id)->where('type', 0)->where('result', 1)->whereRaw('date(created_at) = ?', [Carbon::today()])->count();

        $deposit_unsuccess = AepsTransaction::where('user_id',Auth::user()->id)->where('type', 1)->where('result', 0)->whereRaw('date(created_at) = ?', [Carbon::today()])->count();

        $deposit_success = AepsTransaction::where('user_id',Auth::user()->id)->where('type', 1)->where('result', 1)->whereRaw('date(created_at) = ?', [Carbon::today()])->count();

        $withdraw_unsuccess = AepsTransaction::where('user_id',Auth::user()->id)->where('type', 2)->where('result', 0)->whereRaw('date(created_at) = ?', [Carbon::today()])->count();

        $withdraw_success = AepsTransaction::where('user_id',Auth::user()->id)->where('type', 2)->where('result', 1)->whereRaw('date(created_at) = ?', [Carbon::today()])->count();
    
	return View::make('reports.transaction-reports', ['transactions' => json_encode($txs), 'txObjs' => $transactions, 'balEnqUnsuccess' => $bal_enq_unsuccess, 'balEnqSuccess' => $bal_enq_success, 'depositUnsuccess' => $deposit_unsuccess, 'depositSuccess' => $deposit_success, 'withdrawUnsuccess' => $withdraw_unsuccess, 'withdrawSuccess' => $withdraw_success]);
});

Route::get('/wallet-reports', function () {
	Paginator::setPageName('page');
	$WalletTransaction = new WalletTransaction;
       $WalletTransaction->setConnection('mysql1');

       $BalanceRequest = new BalanceRequest;
       $BalanceRequest->setConnection('mysql1');

	$vendorTransactions = $WalletTransaction->where('user_id', Auth::user()->id)
	->orderBy('id', 'DESC')
	->paginate(100);
	// $adminTransactions = WalletAction::where('user_id', Auth::user()->id)
	// 	->where('parent_id', 0)
	// 	->with('debit')
	// 	->with('credit')
	// 	->get();

	Paginator::setPageName('pag');
	$adminTransactions = $BalanceRequest->where('user_id', Auth::user()->id)
	->orderBy('id', 'DESC')
	->paginate(50);	

	return View::make('reports.wallet-reports', ['vendorTransactions' => $vendorTransactions, 'adminTransactions' => $adminTransactions]);
});

Route::get('/wallet-reports-admin', function () {

	$adminTransactions = BalanceRequest::where('user_id', Auth::user()->id)
	->orderBy('id', 'DESC');

	$adminTransactions = BalanceRequest::select(array(
			'id',
			'created_at',
			'amount',
			'bank', 
			'branch', 
			'transfer_mode', 
			'reference_number', 
			'status'
		))
		->where('user_id', Auth::user()->id)
		->orderBy('id', 'DESC');

	return Datatables::of($adminTransactions)
			->edit_column('transfer_mode', 
				'
					@if($transfer_mode == 1) <span class="label label-primary">IMPS</span>
					@elseif($transfer_mode == 2) <span class="label label-info">NEFT</span> 
					@elseif($transfer_mode == 3) <span class="label label-success">Cash Deposit To Bank</span> 
				 	@else <span class="label label-warning">RTGS</span> 
				 	@endif
				'
			)
			->edit_column('bank', 
				'@if($bank == 7) ICICI BANK @endif'
			)
			->edit_column('created_at', function($result_obj) {
                //in a callback, the Eloquent object is returned so carbon may be used
                return $result_obj->created_at->format('M j, Y g:i a');
            })
			->edit_column('status', 
				'
					@if($status == 0) <span class="label label-warning">Pending</span>
					@elseif($status == 1) <span class="label label-success">Active</span> 
				 	@else <span class="label label-danger">Rejected</span> 
				 	@endif
				'
			)
			->make(true);
});


Route::get('/test1', function ()
{
	var_dump(['code' => 1, 'wer']);

});
Route::get('/support', function ()
{

	$data = MasterSupport::lists('support_name');
	return View::make('support.support', ['support_data' => $data]);

});
Route::post('/support','SupportController@add_support');
Route::get('/support-report','SupportController@support_report');
Route::get('/transaction-report','TransactionsController@transaction_report_distributor');
Route::get('/wallet-report','WalletsController@wallet_report_distributor');
Route::post('/export-example','PagesController@export');
Route::get('/muthootDataSend','TransactionsController@muthootDataSend');
Route::get('/commission-reports','ReportsController@getDistributorCommissionReport');
Route::post('/export-distributors-commission-report', 'ReportsController@getDistributorCommissionExport');
Route::get('/test2', function () {
	$iso = json_decode(Input::get('iso'));
	// $iso = json_decode('{"object":{"iso8583Message":"0210F23A00100EC184000000000000000002196076460906016781226210000000000020000070511432939466911432907050705C00000000718611394669BL900800RDI001980000000RDI0019803209218f144f334f1eb483031c001c49cd3560200002356D00000000000000007804000400800000001100051218Postilion:MetaData1813RRN11113RRN2127186113946699117","isVoidTxn":false},"responseCode":"00","responseMessage":"SUCCESS","requestId":"119874","nextFreshnessFactor":"8069283581739341644"}');
	$isoFactory = new ISO8583();
	dd($isoFactory->parse($iso->object->iso8583Message));
	// dd($isoParsed[1].'\n');
	// {"object":{"iso8583Message":"0210F23800000280800000000000000000221960739309060167812263100000000000000000614121432316807121432061412RDI0000135601521010121314600000002000000000800000009117","isVoidTxn":false},"responseCode":"00","responseMessage":"SUCCESS","requestId":"80","nextFreshnessFactor":"1024771769660633682"}
});


Route::get('/set-rn-number', function () {
	//$transaction_id = ;
	$records = DB::table('aeps_transactions2')->take(100)->get();

	foreach ($records as $record) {
		//
		$checcktran= DB::table('aeps_transaction_logs2')->where('transaction_id','=',$record->id)->first();
 
 
		if($checcktran)
		{
			



				$vari=$checcktran->response;
				//dd($vari);
				//dd($vari[1]);
				if (strpos($vari, 'iso8583Message') != false) { 
					echo $record->id."\n";
  					$vari_jso=json_decode($vari);
					if($vari_jso)
					{
						$isoFactory = new ISO8583();
						
						$compar_iso=$isoFactory->parse($vari_jso->object->iso8583Message);
						//dd($compar_iso);
						$rnn=isset($compar_iso['37']) ? $compar_iso['37']:0;
						$resultData=(isset($compar_iso['39'])=='00') ? 1:0;

						DB::table('aeps_transactions3')->where('id',$record->id)->update(['rrn'=>$rnn,'result'=>$resultData]);
					}
				}
				
		

			}
		

	}
	
});


//Added on 6/9/2017 for sales functionality

Route::get('/agent-sales-executive-report','SalesController@getAgentSalesReport');

Route::get('/distributor-area-sales-report','SalesController@getDistributorAreaSalesOfficerReport');

Route::get('/sales-executive-area-sales-manager-report','SalesController@getSalesExecutiveSalesManagerReport');

Route::get('/distributor-sales-executive-report','SalesController@getDistributorSalesReport');

Route::get('/area-sales-officer-report-for-clustor-head','SalesController@getAreaSalesOfficerForClustorHeadReport');

Route::get('/area-sales-manager-reports-for-state-head','SalesController@getAreaSalesManagerForStateHeadReport');

Route::get('/cluster-head-reports-for-regional-head','SalesController@getClusterHeadForRegionalHeadReport');

Route::get('/cluster-head-reports-for-state-head/{user_id}','SalesController@getClusterHeadForStateHeadReport');


//Route::get('/regional-head-report','SalesController@getStateHeadSalesReport');
Route::get('addagent/{id}','TicketController@addagent');

Route::get('/area-sales-officer-report','SalesController@getAreaSalesOfficerReport');
Route::get('/area-sales-manager-report','SalesController@getAreaSalesManagerReport');
Route::get('/cluster-head-report','SalesController@getClusterHeadReport');
Route::get('/state-head-report','SalesController@getStateHeadReport');
Route::get('/regional-head-report','SalesController@getRegionalHeadReport');

Route::get('/agent-reports-for-area-sales-officer/{user_id}','SalesController@getAgentReportForAreaSalesOfficer');

Route::get('/export-agent-sales-report-for-distributors/{user_id}','SalesController@getDistributorAgentExport');

Route::get('/distributor-sales-executive-date-report','SalesController@getDistributorSalesDateReport');

Route::get('/sales-executive-area-sales-officer-date-report','SalesController@getSalesExecutiveSalesDateReport');

Route::get('/area-sales-officer-area-sales-manager-date-report','SalesController@getSalesOfficeAreaSalesManagerSalesDateReport');

Route::get('/area-sales-manager-cluster-head-date-report','SalesController@getSalesManagerClusterHeadSalesDateReport');

Route::get('/cluster-head-state-head-date-report','SalesController@getClusterHeadStateHeadSalesDateReport');

Route::get('/state-head-regional-head-date-report','SalesController@getStateHeadRegionalHeadSalesDateReport');

Route::get('/cluster-head-regional-head-date-report/{user_id}','SalesController@getClusterHeadRegionalHeadSalesDateReport');

Route::get('/agent-sales-executive-report-for-distributor/{user_id}','SalesController@getAgentSalesReportForDistributor');

Route::get('/sales-executive-date-reports-for-area-sales-manager/{user_id}','SalesController@getSalesExecutiveAreaSalesManagerSalesDateReport');


Route::get('/area-sales-officer-date-reports-for-cluster-head/{user_id}','SalesController@getSalesOfficeClusterHeadDateReport');


Route::get('/distributor-date-report-for-area-sales-officer/{user_id}','SalesController@getDistributorSalesExecutiveSalesDateReport');

Route::get('/agent-distributor-date-report-for-area-sales-officer/{user_id}','SalesController@getAgentDistributorSalesDateReport');

Route::get('/area-sales-manager-date-reports-for-cluster-head/{user_id}','SalesController@getAreaSalesManagerClusterHeadSalesDateReport');







Route::get('/sales-executive-reports-for-area-sales-officer/{user_id}','SalesController@getSalesExecutiveReportForAreaSalesOfficer');

Route::get('/sales-executive-reports-for-area-sales-manager/{user_id}','SalesController@getSalesExecutiveReportForAreaSalesManager');

Route::get('/distributor-reports-for-area-sales-manager/{user_id}','SalesController@getSalesExecutiveReportForAreaSalesOfficer');


Route::get('/area-sales-officer-for-clustor-head/{user_id}','SalesController@getAreaSalesOfficerClustorHeadReport');

Route::get('/area-sales-manager-reports-for-cluster-head/{user_id}','SalesController@getAreaSalesManagerForClusterHeadReport'); 



Route::get('/getauth', 'SessionsController@getauth');
Route::get('/export-agent-sales-report', 'SalesController@getAgentExport');
Route::get('/export-distributor-sales-report', 'SalesController@getDistributorExport');
Route::get('/export-distributor-sales-date-report', 'SalesController@getDistributorDateExport');

Route::get('/export-agent-sales-report-for-distributors', 'SalesController@getAgentExport');

Route::get('/bc-agent-registration', function () {
	return View::make('/bc-agent-registration');
});

Route::get('/create-ticket', function () {
	return View::make('/create-ticket');
});

Route::get('/list-ticket', function () {
	return View::make('/list-ticket');
});

Route::post('/postBcagent', 'BcAgentController@postBcAgentForm'); 

Route::post('/users/{id}/actions/profile/registration/update', 'BcAgentController@postBcagentRegistrationPDF');


Route::get('/bc-agent-report','BcAgentController@getBcAgentReport');

Route::get('/registration-form/{user_id}','BcAgentController@generateRegistrationPDF');

Route::get('/registration-update-pdf-form/{user_id}','BcAgentController@getBcAgentRegistrationData');


Route::get('/download-driver', 'PagesController@downloadDriver');
Route::get('/customer-guidelines', 'PagesController@customerGuidelines');

//playstore register
Route::post('/api/mobile/v1/createPlayStoreUsers', 'MobileApiController@createPlayStoreUsers');

//getlocation

Route::post('api/mobile/v1/agent/deviceLocationInfo', 'MobileApiController@getlocation');//9000
Route::post('api/v1/agent/deviceLocationInfo', 'MobileApiController@getlocation');//live
function getBanksDict ($banks)
{
	$banksDict = [];
	foreach ($banks->toArray() as $bank) {
		$banksDict[$bank['id']] = $bank['name'];
	}
	return $banksDict;
}
function getStatus ($status, $result)
{
	if (($status == 3 || $status == 4) && $result == 0) return 'Failed';
	if (($status == 3 || $status == 4) && $result == 1) return 'Success';
	// if (($status == 0 || $status == 1) && Carbon::parse()
	// @todo ensure the status changes to 4 in case of a timeout
	return 'In progress';
}
function calculateCommission ($type, $userId, $amount)
{
	$rate = calculateCommissionRate($amount, $userId);
	if (! $rate) return false;
	if ($type == 0) {
		return $amount * ($rate->balance_enquiry_rate / 100);
	}
	return $amount * ($rate->rate / 100);
}
function calculateCommissionRate ($amount, $userId)
{
	$master = CommissionMaster::where('min', '<=', $amount)->where('max', '>=', $amount)->first();
	if (! $master) return false;
	$rate = CommissionRate::where('user_id', $userId)->where('master_id', $master->id)->first();
	if (! $rate) return false;
	return $rate;
}

foreach (File::allFiles(__DIR__ . '/routes') as $file){
	require $file->getPathname();
}
/*Route::get('test-dmt-user',function(){
	// code to check user permissions
     $headers = [
        'Accept' => 'application/json',
       'Content-Type' => 'application/json',
      'auth' => \Cookie::get('tracker')
      ];
     
      if(6){
        $response = \Unirest\Request::get(getenv('DMT_URL').'/api/dmt-user/6/v1', $headers);
      }
     
      $parent = $response->body;
      //dd($parent);
      if(!$$parent){
      Session::put('dmt_venor',1);
     }else{
      Session::forget('dmt_venor');
     }
 });*/

 Route::get('/captcha', function () {
	return View::make('sessions.captcha');
});
 Route::get('/device-info', function () {
 	$data = DeviceDetails::where('user_id',Auth::user()->id)->pluck('user_id');
	return View::make('pages.device-info', ['data' => $data]);
});
Route::post('/store-device-details/{id}','PagesController@storeDeviceDetails');

Route::get('/ticket', function () {
	return View::make('ticket');
});

Route::get('/hub', function () {
	return View::make('hub');
});

Route::get('ticketlogin','TicketController@ticketlogin');

Route::get('/serverSide', function () {
 	$users = User::select(array('id','name','email','phone_no'));

	return Datatables::of($users)->make(true);
});


Route::get('/webapilogin','WebapiController@postlogin');


Route::get('/webapilogout','SessionsController@getlogout');

