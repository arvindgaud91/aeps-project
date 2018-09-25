<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;

/**
*
*/
class SettlementController extends HomeController
{
	function __construct()
	{

	}

	
	public function getSettlement ()
	{	
      $Settlement = new Settlement;
       $Settlement->setConnection('mysql1');
		$settlement_details = $Settlement->where('user_id', Auth::user()->id)->latest()->paginate(1);
    //dd($settlement_details);
  return View::make('settlement.settlement-details',['settlement_details'=>$settlement_details]);
}
public function postSettlement ()
{	
//dd(Input::all());
	$beneficiary_name=Input::get('beneficiary_name');
	$account_number=Input::get('account_number');
	$ifsc_code=Input::get('ifsc_code');
	$bank_name=Input::get('bank_name');

	$settlement_type=0;//Input::get('settlement_type');

	$validator = Validator::make(array(
		'beneficiary_name'=>$beneficiary_name,
		'account_number'=>$account_number,
		'ifsc_code'=>$ifsc_code,
		'bank_name'=>$bank_name,
		'settlement_type'=>$settlement_type,
		'user_id'=>Auth::user()->id
	), array(
		'beneficiary_name' => 'required',
		'account_number' => 'required',
		'ifsc_code' => 'required',
		'bank_name' => 'required',
		'settlement_type' => 'required',
		'user_id'=>'required | unique:settlement_bank_account'
	));
	if ($validator->fails())
		return Response::json($validator->messages(), 500);

	$settlement_status=Settlement::create([
		'user_id'=>Auth::user()->id,
		'beneficiary_name'=>$beneficiary_name,
		'account_number'=>$account_number,
		'ifsc_code'=>$ifsc_code,
		'bank_name'=>$bank_name,
		'settlement_type'=>$settlement_type,
		'created_at'=>date("Y-m-d H:i:s")
	]);
	if($settlement_status){
		Session::forget('settlement');
	}
	return Response::json('true', 200);
}
public function postSettlementRequest ()
{	
	if (ClosingBalance::where('user_id',Auth::user()->id)->latest()->pluck('settlement_status') == 1) 
		return Response::json(['code' => 3], 422);
	$settlement_bank_id=Settlement::where('user_id',Auth::user()->id)->latest()->pluck('id');

	if (!$settlement_bank_id) 
		return Response::json(['code' => 4], 422);

	$total_balance=Input::get('available_balance');
	$transaction_amount=Input::get('transaction_amount');
	$remaining_balance=Input::get('remaining_balance');

	$validator = Validator::make(array(
		'transaction_amount'=>$transaction_amount,
		'remaining_amount'=>$remaining_balance
	), array(
		'transaction_amount' => 'required',
		'remaining_amount' => 'required'
	));
	if ($validator->fails())
		return Response::json($validator->messages(), 500);

	if (! $transaction_amount || ! $remaining_balance)
		return Response::json(['code' => 1], 422);

	if(($total_balance-$transaction_amount)<100)
		return Response::json('false', 500);

		    //$settlementRequest = Input::only('remaining_amount', 'transaction_amount', 'available_balance');

	$closing_balance = ClosingBalance::where('user_id',Auth::user()->id)->latest()->pluck('balance');
	
	if ($closing_balance < $transaction_amount) return Response::json(['code' => 2], 422);

	$vendor = Vendor::where('user_id', Auth::user()->id)->lockForUpdate()->first();
	if ($vendor->balance < $transaction_amount) return Response::json(['code' => 2], 422);

	ClosingBalance::where('user_id',Auth::user()->id)->update(['settlement_status'=>1]);

		
		$vendor->balance -= $transaction_amount;
		$debit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 0, 'amount' =>  $transaction_amount, 'balance' => $vendor->balance,'activity' => 'debited', 'narration' =>'Settlement-request']);

		WalletAction::create([
			'user_id' => $vendor->user_id,
		      'counterpart_id' => 1,//Admin id
		      'amount' => $transaction_amount,
		      'status' => 1,
		      'debit_id' => $debit->id,
		      'wallet_request_id' => 0,
		      'type' => 0,
		      'admin' => false,
		      'automatic' => false,
		      'remarks' => 'Settlement-request'
		  ]);
//dd($settlement_bank_id);
		$settlement_status=SettlementRequest::create([
			'user_id'=>Auth::user()->id,
			'transaction_amount'=>$transaction_amount,
			'remaining_amount'=>$remaining_balance,
			'settlement_bank_id'=>$settlement_bank_id,
			'transaction_id'=>$debit->id,
			'created_at'=>date("Y-m-d H:i:s")
		]);
		//dd($settlement_bank_id);
		$vendor->save();
		return Response::json('true', 200);
}

public function getSettlementReport ()
{	
	Paginator::setPageName('page');
    $Settlement = new  Settlement;
       $Settlement->setConnection('mysql1');

	$data = $Settlement->where('settlement_request.user_id','=',Auth::user()->id)
	/*->Join('settlement_request','settlement_request.user_id','=','settlement_bank_account.user_id')*/
	->Join('settlement_request', function($join){
            $join->on('settlement_request.user_id','=','settlement_bank_account.user_id');
            $join->on('settlement_request.settlement_bank_id','=','settlement_bank_account.id');
        })
	->Join('users','users.id','=','settlement_request.user_id')
	->select('users.name',DB::raw("(CASE
		WHEN settlement_request.status=0 THEN 'Pending'
		WHEN settlement_request.status=1 THEN 'Inprogress'
		WHEN settlement_request.status=2 THEN 'Disbursed'
		WHEN settlement_request.status=3 THEN 'Rejected'
		END) as status"),'settlement_request.status as s','settlement_bank_account.beneficiary_name','settlement_bank_account.account_number','settlement_request.transaction_amount as amount','settlement_request.created_at','settlement_request.id')->orderBy('settlement_request.id','desc')
	->paginate(100);

	return View::make('settlement.settlement-report', ['settlement_data' => $data]);
}
public function getSettlementRequest ()
{	
//dd(date('Y-m-d',strtotime("-1 days")));
	//$user_balance=ClosingBalance::where('user_id',Auth::user()->id)->latest()->pluck('balance');

	 $Settlement = new Settlement;
       $Settlement->setConnection('mysql1');

       $Vendor = new Vendor;
       $Vendor->setConnection('mysql1');


$ClosingBalance = new ClosingBalance;
       $ClosingBalance->setConnection('mysql1');
       

	$user_balance=$Vendor->where('user_id',Auth::user()->id)->pluck('balance');
	$closing_balance=$ClosingBalance->where('user_id',Auth::user()->id)->where('settlement_status',0)->latest()->pluck('balance');
	$closing_balance=$closing_balance ? $closing_balance:0;
	$settlement_request=$Settlement->where('user_id',Auth::user()->id)->latest();
	if($settlement_request)
	{
		$settlement_request1=1;
	}else
	{
		$settlement_request1=0;
	}
	return View::make('settlement.settlement-request',['user_balance'=>$user_balance,'closing_balance'=>$closing_balance,'settlement_request'=>$settlement_request1]);
}

public function autoSettlementRequest ()
{	
	$auto_settlement_users=Settlement::where('settlement_type',1)->get();
	foreach($auto_settlement_users as $user){
	
	$closing_balance = ClosingBalance::where('user_id',$user->user_id)->where('settlement_status','0')->latest()->pluck('balance');

	$transaction_amount=$closing_balance-100;
//dd($transaction_amount);
	$vendor = Vendor::where('user_id', $user->user_id)->lockForUpdate()->first();
	
		if($vendor->balance>=$closing_balance && $transaction_amount>=100){
			ClosingBalance::where('user_id',$user->user_id)->update(['settlement_status'=>1]);
			$vendor->balance -= $transaction_amount;
		$debit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 0, 'amount' =>  $transaction_amount, 'balance' => $vendor->balance,'activity' => 'debited', 'narration' =>'Auto-settlement-request']);

		WalletAction::create([
			'user_id' => $vendor->user_id,
		      'counterpart_id' => 1,//Admin id
		      'amount' => $transaction_amount,
		      'status' => 1,
		      'debit_id' => $debit->id,
		      'wallet_request_id' => 0,
		      'type' => 0,
		      'admin' => false,
		      'automatic' => false,
		      'remarks' => 'Auto-settlement-request'
		  ]);

		$settlement_status=SettlementRequest::create([
			'user_id'=>$user->user_id,
			'transaction_amount'=>$transaction_amount,
			'remaining_amount'=>$vendor->balance,
			'settlement_bank_id'=>$user->id,
			'transaction_id'=>$debit->id,
			'created_at'=>date("Y-m-d H:i:s")
		]);
		//dd($settlement_bank_id);
		$vendor->save();
		}
		
	}
		return Response::json('true', 200);
}
}
