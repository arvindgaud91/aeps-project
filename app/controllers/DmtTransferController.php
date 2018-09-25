<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;
use Acme\Helper\Export;
/**
*
*/
class DmtTransferController extends HomeController
{
	function __construct()
	{

	}

	public function getDmtTransferRequest ($id)
	{	
		if($id!=Auth::user()->id){
			Redirect::back();
		}

		$user_balance=Vendor::where('user_id',$id)->pluck('balance');
    	//dd($user_balance);
		return View::make('transfer-to-dmt.transfer-request',['user_balance'=>$user_balance]);
	}
	public function postDmtTransferRequest ()
	{	
		if(!Auth::user())
			return Response::json(['message' => 'User Invalid'], 422);
		//$transfer_data=Input::all();

			// $headers = [
			// 	'Accept' => 'application/json',
			// 	'Content-Type' => 'application/json',
			// 	'auth' => \Cookie::get('tracker')
			// ];

			// $body = \Unirest\Request\Body::json($transfer_data);
			// $response = \Unirest\Request::post(getenv('DMT_URL').'/api/transfer/v1/'.Auth::user()->id, $headers, $body); 
			
		 //    if($response->body->code==3)
			// return Response::json(['code' => 3], 422);

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
			return Response::json(['code' => 1], 422);

		$vendor = Vendor::where('user_id', Auth::user()->id)->lockForUpdate()->first();

		if(($vendor->balance-$transaction_amount)<100)
			return Response::json('balance is not enough', 2);

		if ($vendor->balance < $transaction_amount) return Response::json(['code' => 2], 422);


			$vendor->balance -= $transaction_amount;
			$vendor->save();
			$debit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 0, 'amount' =>  $transaction_amount, 'balance' => $vendor->balance,'activity'=>'debited','narration'=>'Transferred to dmt']);
//dd("000");
			WalletAction::create([
				'user_id' => $vendor->user_id,
				'counterpart_id' => "0",
				'amount' => $transaction_amount,
				'status' => 1,
				'debit_id' => $debit->id,
				'wallet_request_id' => 0,
				'type' => 0,
				'admin' => false,
				'automatic' => false,
				'remarks' => 'Transfer to dmt'
			]);

			AepsToDmt::create([
				'user_id'=>Auth::user()->id,
				'transaction_amount'=>$transaction_amount,
				'remaining_amount'=>$remaining_balance,
				'transaction_id'=>$debit->id,
				'created_at'=>date("Y-m-d H:i:s")
			]);
			return Response::json('true', 200);
		}

		public function getDmtTransferReport ()
		{	
			$user = Auth::user();

			Paginator::setPageName('page');
			$userIds[0] = Auth::user()->id;
			$Vendor=new Vendor;
       $Vendor->setConnection('mysql1');

       $User=new User;
       $User->setConnection('mysql1');

       $WalletAction = new WalletAction;
       $WalletAction->setConnection('mysql1');

        $WalletTransaction = new WalletTransaction;
       $WalletTransaction->setConnection('mysql1');

       

			$userIds = $Vendor->where('parent_id', Auth::user()->id)->lists('user_id');
			$userIds[sizeof($userIds) + 1] = Auth::user()->id;
			$walletsObj = $WalletAction->where('user_id', Auth::user()->id)->where('counterpart_id', 0)->where('remarks', 'Transfer to dmt')->orderBy('id', 'DESC')->paginate(100);

			$wallets = $walletsObj->getItems();
			$wallets = array_map(function ($w) {
					$Vendor=new Vendor;
       $Vendor->setConnection('mysql1');

       $User=new User;
       $User->setConnection('mysql1');

       $WalletAction = new WalletAction;
       $WalletAction->setConnection('mysql1');

        $WalletTransaction = new WalletTransaction;
       $WalletTransaction->setConnection('mysql1');
				$w->transaction = $w->debit ? $w->debit : ($w->credit ? $w->credit : null);
				$w->referenceNumber = $WalletTransaction->where('id', $w->transaction_id)->first();
				$w->activity = "Transaction";
				return $w;
			}, $wallets);
			$username = $User->get();
			$allusername;
			foreach ($username as $user) {
				$allusername[$user->id] = $user->name;
			}
        // dd($allusername);
			return View::make('transfer-to-dmt.transfer-to-dmt-report', ['wallets' => $wallets, 'walletsObj' => $walletsObj, 'allusername' => $allusername]);
		}

		public function getExportDmtTransferReport() {
			if ((Input::get('from_date') && Input::get('to_date'))) {
				$start_date = date("Y-m-d", strtotime(Input::get('from_date')));
				$end_date = date("Y-m-d", strtotime(Input::get('to_date')));
				$start_date_time = $start_date.'00:00:00';
				$end_date_time = $end_date.'23:59:59';


       $WalletAction = new WalletAction;
       $WalletAction->setConnection('mysql1');

        



				$records = $WalletAction->whereBetween('wallet_actions.created_at', [$start_date, $end_date])->where('wallet_actions.user_id', Auth::user()->id)->where('wallet_actions.counterpart_id', 0)->where('wallet_actions.remarks', 'Transfer to dmt')->select('wallet_actions.created_at', DB::raw("(if(wallet_actions.debit_id=0 and wallet_actions.credit_id !=0, CONCAT('+',wallet_actions.amount), CONCAT('-',wallet_actions.amount))) as amount,(SELECT balance FROM wallet_transactions WHERE id=if(wallet_actions.debit_id=0 and wallet_actions.credit_id !=0, wallet_actions.credit_id,wallet_actions.debit_id))  as balance "), 'wallet_actions.remarks')->leftjoin('users', 'wallet_actions.counterpart_id', '=', 'users.id')
				->orderBy('wallet_actions.id', 'DESC')->get()->toArray();
			} 
			$export_csv= new Export();
			$export_csv->exportData($records,"wallet-report-");
		}

public function getReport ()
{ 
   Paginator::setPageName('page');
   $queryObj = AepsToDmt::where('user_id',Auth::user()->id)->
   select(DB::raw("(CASE
    WHEN aeps_to_dmt_request.status=0 THEN 'Pending'
    WHEN aeps_to_dmt_request.status=1 THEN 'Done'
    WHEN aeps_to_dmt_request.status=2 THEN 'Disbursed'
    WHEN aeps_to_dmt_request.status=3 THEN 'Rejected'
    END) as status"),'aeps_to_dmt_request.status as s','aeps_to_dmt_request.transaction_amount as amount','aeps_to_dmt_request.created_at','aeps_to_dmt_request.id','aeps_to_dmt_request.user_id','aeps_to_dmt_request.remaining_amount as balance')->orderBy('id','desc')->paginate(100);

      //dd();
   return View::make('transfer-to-dmt.transfer-to-dmt-report', ['wallets' => $queryObj->getItems(), 'walletsObj' => $queryObj]);
 }

	}
