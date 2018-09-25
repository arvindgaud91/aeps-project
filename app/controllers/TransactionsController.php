<?php
use Acme\Auth\Auth;
use Acme\Helper\Rabbit;
use Acme\Helper\GateKeeper;
use Carbon\Carbon;
use Acme\Helper\Export;
use Acme\SMS\SMS;

class TransactionsController extends BaseController
{
  public function getTransactionForm()
  {
    $data['transactionType'] = Input::get('type');
    $parent_id=Vendor::where('user_id',Auth::user()->id)->pluck('parent_id');

    $data['distributorType'] = ($parent_id == 1024) ? 'muthoot' :'other';
    $data['banks'] = Bank::get();
    return View::make('transactions.transaction')->with($data);
  }

  public function postBalanceEnquiry ()
  {

   
 GateKeeper::checkRoles(Auth::user(), 1, true);
  $vendor = Vendor::where('user_id', Auth::user()->id)->first();
  if($vendor->device_service == 0)
   {
      
    $validator = Validator::make(Input::all(), [
      'aadhar_no' => 'required|integer',
      'bank_iin' => 'required|integer',
      'fingerprint' => 'required',
    ]);
    if ($validator->fails())
      return Response::json($validator->messages(), 422);

    // if (! $this->limitTransactions(Input::get('aadhar_no'), 0))
    //   return Response::json(['code' => 0, 'message' => 'Maximum number of Balance Enquiries for Aadhar number '.Input::get('aadhar_no').' reached'], 422);
  
  //Different ID
    $transactionSubmitted = array_merge(Input::only('aadhar_no', 'bank_iin','remark1','remark2', 'fingerprint'), ['action' => 'balance_enquiry','device_service' =>$vendor->device_service,'amount' => 0, 'stan' => incrementalHash(),'user_id' =>Auth::user()->id , 'm_action' =>'B']);
  
  //FORAG45
  //$transactionSubmitted = array_merge(Input::only('aadhar_no', 'bank_iin','remark1','remark2', 'fingerprint'), ['action' => 'balance_enquiry', 'amount' => 0, 'stan' => incrementalHash()]);

    $vendor = Vendor::where('user_id', Auth::user()->id)->first();
    $transaction = AepsTransaction::create([
      'user_id' => Auth::user()->id,
      'bank_id' => Input::get('bank_id'),
      'aadhar_no' => $transactionSubmitted['aadhar_no'],
      'type' => 0,
      'status' => 0,
      'stan' => $transactionSubmitted['stan'],
      'transaction_source' =>'web',
      'cust_name' =>Input::get('cust_name'),
      'cust_phone_no' =>Input::get('cust_phone_no')
    ]);
    if ($this->transact($transactionSubmitted, $vendor, $transaction->id)) {
      return Response::json([
        'transaction_id' => $transaction->id,
        'aadhar_no' => $transactionSubmitted['aadhar_no'],
      ], 200);
    }
    return Response::json("Transaction request to queue failed.", 500);

   }
   if($vendor->device_service == 1)
   {

    $validator = Validator::make(Input::all(), [
      'aadhar_no' => 'required|integer',
      'bank_iin' => 'required|integer',
      //'fingerprint' => 'required',
      'pid' =>'required',
    ]);
    if ($validator->fails())
      return Response::json($validator->messages(), 422);

    // if (! $this->limitTransactions(Input::get('aadhar_no'), 0))
    //   return Response::json(['code' => 0, 'message' => 'Maximum number of Balance Enquiries for Aadhar number '.Input::get('aadhar_no').' reached'], 422);
    
  $vendor = Vendor::where('user_id', Auth::user()->id)->first();
    $transactionSubmitted = array_merge(Input::only('aadhar_no', 'bank_iin', 'mc', 'udc', 'dpId', 'rdsId', 'rdsVer', 'dc', 'mi', 'pid','remark1','remark2'), ['action' => 'balance_enquiry','device_service' =>$vendor->device_service, 'amount' => 0, 'stan' => incrementalHash(),'user_id' =>Auth::user()->id , 'm_action' =>'B']);
  
    $transaction = AepsTransaction::create([
      'user_id' => Auth::user()->id,
      'bank_id' => Input::get('bank_id'),
      'aadhar_no' => $transactionSubmitted['aadhar_no'],
      'type' => 0,
      'status' => 0,
      'stan' => $transactionSubmitted['stan'],
      'transaction_source' =>'web',
       'cust_name' =>Input::get('cust_name'),
      'cust_phone_no' =>Input::get('cust_phone_no')
    ]);
    if ($this->transact($transactionSubmitted, $vendor, $transaction->id)) {
      return Response::json([
        'transaction_id' => $transaction->id,
        'aadhar_no' => $transactionSubmitted['aadhar_no'],
      ], 200);
    }
    return Response::json("Transaction request to queue failed.", 500);

   } 

    
    
  }


  public function postDeposit ()
  {
    GateKeeper::checkRoles(Auth::user(), 1, true);
   
  $vendor = Vendor::where('user_id', Auth::user()->id)->first();
  if($vendor->device_service == 0)
   {
   
    $validator = Validator::make(Input::all(), [
      'amount' => 'required|integer',
      'aadhar_no' => 'required|integer',
      'bank_iin' => 'required|integer',
       'fingerprint' => 'required',
    ]);
    if ($validator->fails())
      return Response::json($validator->messages(), 422);

    // if (! $this->limitTransactions(Input::get('aadhar_no'), 1))
    //   return Response::json(['code' => 0, 'message' => 'Maximum number of Deposits for Aadhar number '.Input::get('aadhar_no').' reached'], 422);

	//For Different ID
    $transactionSubmitted = array_merge(Input::only('aadhar_no', 'bank_iin', 'fingerprint', 'amount','remark1','remark2'), ['action' => 'deposit','device_service' =>$vendor->device_service ,'stan' => incrementalHash(),'user_id' =>Auth::user()->id , 'm_action' =>'R']);
	
	//$transactionSubmitted = array_merge(Input::only('aadhar_no', 'bank_iin', 'fingerprint', 'amount','remark1','remark2'), ['action' => 'deposit', 'stan' => incrementalHash()]);
	

    $vendor = Vendor::where('user_id', Auth::user()->id)->first();
    if($vendor->master_wallet_id == 1)
     {
      $distributordetails=Vendor::where('user_id',$vendor->parent_id)->first();

      if ($distributordetails->balance < $transactionSubmitted['amount']) return Response::json(['code' => 1, 'message' => 'Insufficient Balance to perform a Deposit transaction.'], 422);


     }else
     {

if ($vendor->balance < $transactionSubmitted['amount']) return Response::json(['code' => 1, 'message' => 'Insufficient Balance to perform a Deposit transaction.'], 422);


     } 

    
    $transaction = AepsTransaction::create([
      'user_id' => Auth::user()->id,
      'bank_id' => Input::get('bank_id'),
      'aadhar_no' => $transactionSubmitted['aadhar_no'],
      'amount' => $transactionSubmitted['amount'],
      'type' => 1,
      'status' => 0,
      'stan' => $transactionSubmitted['stan'],
      'transaction_source' =>'web',
      'cust_name' =>Input::get('cust_name'),
      'cust_phone_no' =>Input::get('cust_phone_no')
    ]);
    if ($this->transact($transactionSubmitted, $vendor, $transaction->id)) {
      return Response::json([
        'transaction_id' => $transaction->id,
        'aadhar_no' => $transactionSubmitted['aadhar_no'],
        'amount' => $transactionSubmitted['amount'],
        'transaction_id' => $transaction->id
      ], 200);
    }
    return Response::json("Transaction request to queue failed.", 500);
  }

  if($vendor->device_service == 1)
   {

    $validator = Validator::make(Input::all(), [
      'amount' => 'required|integer',
      'aadhar_no' => 'required|integer',
      'bank_iin' => 'required|integer',
      // 'fingerprint' => 'required',
      'pid' =>'required'
    ]);
    if ($validator->fails())
      return Response::json($validator->messages(), 422);

    // if (! $this->limitTransactions(Input::get('aadhar_no'), 1))
    //   return Response::json(['code' => 0, 'message' => 'Maximum number of Deposits for Aadhar number '.Input::get('aadhar_no').' reached'], 422);

    $transactionSubmitted = array_merge(Input::only('aadhar_no', 'bank_iin', 'mc', 'udc', 'dpId', 'rdsId', 'rdsVer', 'dc', 'mi', 'pid', 'amount','remark1','remark2'), ['action' => 'deposit', 'device_service' =>$vendor->device_service,'stan' => incrementalHash(),'user_id' =>Auth::user()->id , 'm_action' =>'R']);
    $vendor = Vendor::where('user_id', Auth::user()->id)->first();

     if($vendor->master_wallet_id == 1)
     {
      $distributordetails=Vendor::where('user_id',$vendor->parent_id)->first();

      if ($distributordetails->balance < $transactionSubmitted['amount']) return Response::json(['code' => 1, 'message' => 'Insufficient Balance to perform a Deposit transaction.'], 422);


     }else
     {

if ($vendor->balance < $transactionSubmitted['amount']) return Response::json(['code' => 1, 'message' => 'Insufficient Balance to perform a Deposit transaction.'], 422);


     } 
    $transaction = AepsTransaction::create([
      'user_id' => Auth::user()->id,
      'bank_id' => Input::get('bank_id'),
      'aadhar_no' => $transactionSubmitted['aadhar_no'],
      'amount' => $transactionSubmitted['amount'],
      'type' => 1,
      'status' => 0,
      'stan' => $transactionSubmitted['stan'],
      'transaction_source' =>'web',
      'cust_name' =>Input::get('cust_name'),
      'cust_phone_no' =>Input::get('cust_phone_no')
    ]);
    if ($this->transact($transactionSubmitted, $vendor, $transaction->id)) {
      return Response::json([
        'transaction_id' => $transaction->id,
        'aadhar_no' => $transactionSubmitted['aadhar_no'],
        'amount' => $transactionSubmitted['amount'],
        'transaction_id' => $transaction->id
      ], 200);
    }
    return Response::json("Transaction request to queue failed.", 500);

   }

  }

  public function postWithdraw ()
  {

  GateKeeper::checkRoles(Auth::user(), 1, true);
   $vendor = Vendor::where('user_id', Auth::user()->id)->first();
  if($vendor->device_service == 0)
   {
    $validator = Validator::make(Input::all(), [
      'amount' => 'required|integer',
      'aadhar_no' => 'required|integer',
      'bank_iin' => 'required|integer',
      'fingerprint' => 'required',
    ]);
    if ($validator->fails())
      return Response::json($validator->messages(), 422);

    // if (! $this->limitTransactions(Input::get('aadhar_no'), 2))
    //   return Response::json(['code' => 0, 'message' => 'Maximum number of Withdrawals for Aadhar number '.Input::get('aadhar_no').' reached'], 422);

	//For Different ID
    $transactionSubmitted = array_merge(Input::only('aadhar_no', 'bank_iin', 'fingerprint','remark1','remark2', 'amount'), ['action' => 'withdraw','device_service' =>$vendor->device_service ,'stan' => incrementalHash(),'user_id' =>Auth::user()->id , 'm_action' =>'P']);
	
	//$transactionSubmitted = array_merge(Input::only('aadhar_no', 'bank_iin', 'fingerprint','remark1','remark2', 'amount'), ['action' => 'withdraw', 'stan' => incrementalHash()]);
	
    $vendor = Vendor::where('user_id', Auth::user()->id)->first();

    $transaction = AepsTransaction::create([
      'user_id' => Auth::user()->id,
      'bank_id' => Input::get('bank_id'),
      'aadhar_no' => $transactionSubmitted['aadhar_no'],
      'amount' => $transactionSubmitted['amount'],
      'type' => 2,
      'status' => 0,
      'stan' => $transactionSubmitted['stan'],
      'transaction_source' =>'web',
      'cust_name' =>Input::get('cust_name'),
      'cust_phone_no' =>Input::get('cust_phone_no')
    ]);
    if ($this->transact($transactionSubmitted, $vendor, $transaction->id)) {
      return Response::json([
        'transaction_id' => $transaction->id,
        'aadhar_no' => $transactionSubmitted['aadhar_no'],
        'amount' => $transactionSubmitted['amount']
      ], 200);
    }
    return Response::json("Transaction request to queue failed.", 500);
  }

  if($vendor->device_service == 1)
  {
    
    $validator = Validator::make(Input::all(), [
      'amount' => 'required|integer',
      'aadhar_no' => 'required|integer',
      'bank_iin' => 'required|integer',
      'pid' => 'required',
    ]);
    if ($validator->fails())
      return Response::json($validator->messages(), 422);

    // if (! $this->limitTransactions(Input::get('aadhar_no'), 2))
    //   return Response::json(['code' => 0, 'message' => 'Maximum number of Withdrawals for Aadhar number '.Input::get('aadhar_no').' reached'], 422);

    $transactionSubmitted = array_merge(Input::only('aadhar_no', 'bank_iin', 'mc', 'udc', 'dpId', 'rdsId', 'rdsVer', 'dc', 'mi', 'pid', 'amount','remark1','remark2'), ['action' => 'withdraw','device_service' =>$vendor->device_service, 'stan' => incrementalHash(),'user_id' =>Auth::user()->id , 'm_action' =>'P']);
    $vendor = Vendor::where('user_id', Auth::user()->id)->first();
    $transaction = AepsTransaction::create([
      'user_id' => Auth::user()->id,
      'bank_id' => Input::get('bank_id'),
      'aadhar_no' => $transactionSubmitted['aadhar_no'],
      'amount' => $transactionSubmitted['amount'],
      'type' => 2,
      'status' => 0,
      'stan' => $transactionSubmitted['stan'],
      'transaction_source' =>'web',
       'cust_name' =>Input::get('cust_name'),
      'cust_phone_no' =>Input::get('cust_phone_no')
    ]);
    if ($this->transact($transactionSubmitted, $vendor, $transaction->id)) {
      return Response::json([
        'transaction_id' => $transaction->id,
        'aadhar_no' => $transactionSubmitted['aadhar_no'],
        'amount' => $transactionSubmitted['amount']
      ], 200);
    }
    return Response::json("Transaction request to queue failed.", 500);

  }
}

  public function getPollTransaction ($txId)
  {
    $tx = AepsTransaction::find($txId);
    if (! $tx) return Response::json(['code' => 1, 'message' => 'Transaction not found'], 422);
    if ($tx->status == 4 && $tx->result == 0) return ['status' => 'fail'];
    if ($tx->status == 4 && $tx->result == 1) return ['status' => 'success'];
    return ['status' => 'pending'];
  }
  public function transaction_report_distributor(){
        $parent_id=Vendor::where('user_id',Auth::user()->id)->pluck('parent_id');
        $distributorType = ($parent_id == 1024) ? 'muthoot' :'other';
       Paginator::setPageName('page');
       if(Auth::user()->isSuperDistributor()){
           
           $distributor_userIds = Vendor::where('parent_id', Auth::user()->id)->lists('user_id'); 
           $userIds = Vendor::whereIn('parent_id', $distributor_userIds)->lists('user_id'); 
       }
        if(Auth::user()->isDistributor()){
          $userIds = Vendor::where('parent_id', Auth::user()->id)->lists('user_id');
       }
       
      $transactionsObj = AepsTransaction::leftjoin('third_party_logs', 'aeps_transactions.id', '=', 'third_party_logs.transaction_id')->leftjoin('users', 'aeps_transactions.user_id', '=', 'users.id')
       ->whereIn('user_id', $userIds)
       ->select('users.name', 'users.phone_no','aeps_transactions.bank_id','aeps_transactions.created_at','aeps_transactions.amount','aeps_transactions.status','aeps_transactions.remarks','aeps_transactions.type','aeps_transactions.result','aeps_transactions.id','third_party_logs.response')->orderBy('aeps_transactions.id', 'DESC')->paginate(100);
       $transactions = $transactionsObj->getItems();
      
      $bal_enq_unsuccess = AepsTransaction::where('user_id',Auth::user()->id)->where('type', 0)->where('result', 0)->whereRaw('date(created_at) = ?', [Carbon::today()])->count();
    
        $bal_enq_success = AepsTransaction::where('user_id',Auth::user()->id)->where('type', 0)->where('result', 1)->whereRaw('date(created_at) = ?', [Carbon::today()])->count();

        $deposit_unsuccess = AepsTransaction::where('user_id',Auth::user()->id)->where('type', 1)->where('result', 0)->whereRaw('date(created_at) = ?', [Carbon::today()])->count();

        $deposit_success = AepsTransaction::where('user_id',Auth::user()->id)->where('type', 1)->where('result', 1)->whereRaw('date(created_at) = ?', [Carbon::today()])->count();

        $withdraw_unsuccess = AepsTransaction::where('user_id',Auth::user()->id)->where('type', 2)->where('result', 0)->whereRaw('date(created_at) = ?', [Carbon::today()])->count();

        $withdraw_success = AepsTransaction::where('user_id',Auth::user()->id)->where('type', 2)->where('result', 1)->whereRaw('date(created_at) = ?', [Carbon::today()])->count();
      
       return View::make('reports.transaction-reports-distributor', ['transactions' => $transactions,'distributorType'=>$distributorType,'transactionsObj' => $transactionsObj, 'balEnqUnsuccess' => $bal_enq_unsuccess, 'balEnqSuccess' => $bal_enq_success, 'depositUnsuccess' => $deposit_unsuccess, 'depositSuccess' => $deposit_success, 'withdrawUnsuccess' => $withdraw_unsuccess, 'withdrawSuccess' => $withdraw_success]);
    
  }
  public function export() {

            $muthoot_response_column = 'aeps_transactions.id';
           
      if(Auth::user()->isSuperDistributor()){
           
           $distributor_userIds = Vendor::where('parent_id', Auth::user()->id)->lists('user_id'); 
           $userIds = Vendor::whereIn('parent_id', $distributor_userIds)->lists('user_id'); 
       }
        if(Auth::user()->isDistributor()){
          $userIds = Vendor::where('parent_id', Auth::user()->id)->lists('user_id'); 
          $parent_id=Vendor::where('user_id',Auth::user()->id)->pluck('parent_id');
            $muthoot_response_column;
          if($parent_id == 1024){
             $muthoot_response_column = 'third_party_logs.response';
             //dd($muthoot_response_column);
           }
       }
        if ((Input::get('from_date') && Input::get('to_date'))) {
            $start_date = date("Y-m-d", strtotime(Input::get('from_date')));
            $end_date = date("Y-m-d", strtotime(Input::get('to_date')));
            $start_date_time = $start_date.' 00:00:00';
            $end_date_time = $end_date.' 23:59:59';
            $records = AepsTransaction::leftjoin('third_party_logs', 'aeps_transactions.id', '=', 'third_party_logs.transaction_id')->whereBetween('aeps_transactions.created_at', [$start_date_time, $end_date_time])->leftjoin('users', 'aeps_transactions.user_id', '=', 'users.id')
            ->whereIn('user_id', $userIds)
       ->select('users.name as Name', 'users.phone_no as Mobile_Number','aeps_transactions.id as Transaction_Id','aeps_transactions.created_at as Transaction_Date','aeps_transactions.amount as Amount',DB::raw("(if((aeps_transactions.status=4 or aeps_transactions.status=3 or aeps_transactions.status=0) and aeps_transactions.result=0, 'Failed', 'Success')) as Status,(CASE
         WHEN aeps_transactions.type=0 THEN 'Balance request'
         WHEN aeps_transactions.type=1 THEN 'Deposit request'
         WHEN aeps_transactions.type=2 THEN 'Withdraw request'
         WHEN aeps_transactions.type=3 THEN 'To Pay request'
         END) as Type"),'aeps_transactions.remarks as Remarks',$muthoot_response_column)->orderBy('aeps_transactions.id', 'DESC')->get()->toArray();
        } 
        $export_csv= new Export();
        $export_csv->exportData($records,"transaction-report-");
    }
  public function getTransactionReceipt ($id)
  {
    $transaction = AepsTransaction::where('id', $id)->with('user.vendorDetails')->first();
    $distId = User::find($transaction->user_id)->vendorDetails->parent_id;
    $superDistId = User::find($distId)->vendorDetails->parent_id;
    if ($transaction->user_id != Auth::user()->id && $distId != Auth::user()->id && $superDistId != Auth::user()->id)
      return Redirect::to('/');
    $aadhaar_code = AadhaarCode::where('response_code', $transaction->result_code)->first();
    $transaction->result_message = $aadhaar_code ? $aadhaar_code->description : '';
    $data['transaction'] = $transaction;
    return View::make('receipts.receipt')->with($data);
  }

  private function transact ($transactionSubmitted, $vendor, $transactionId)
  {
    $payload = array_merge($transactionSubmitted, [
      'csr_id' => $vendor->csr_id,
      'freshness_factor' => $vendor->freshness_factor,
      'transaction_id' => $transactionId,
      'device_id' => $vendor->device_id,
      'terminal_id' => $vendor->terminal_id
    ]);
    // send $payload to queue
    $rabbitQueue = new Rabbit('aeps_transactions');
    try {
      $rabbitQueue->publish($payload);
    } catch (Exception $err) {
      Log::info("Publish to Rabbit queue failed.");
      return false;
    }
    return true;
  }

  private function limitTransactions ($aadhar_no, $transaction_type) {
    return AepsTransaction::where('aadhar_no', $aadhar_no)
      ->where('type', $transaction_type)
      ->where('status', '>', 0)
      ->whereDate('created_at', '=', Carbon::today()->toDateString())
      ->count() < 5 ? true : false;
  }

  private function validateTerminalId ($terminal_id)
  {
    if (Auth::user()->vendorDetails->terminal_id == $terminal_id)
      return true;
    return false;
  }
  public function sendRefundOTP ($transaction_id)
  {
    $cust_phone_no = AepsTransaction::where('id', $transaction_id)->where('refund_status', 1)
     ->pluck('cust_phone_no');
    if (!$cust_phone_no) {
      return Response::json(['message' => 'Missing info'], 422);
    }
    // $user = User::where('id',  $user_id)
    //   ->where('type', 4)
    //   ->first();
    // if (! $user) return Response::json(['code' => 1], 422);
    $otp = mt_rand(1000, 9999);
    $otpObj = new RefundOTP(['transaction_id' => $transaction_id, 'otp' => $otp.'', 'ip' => Request::getClientIp()]);
    $otpObj->save();
    // @todo send SMS
    // SMS the OTP
    //$response = \Unirest\Request::post("http://sms.hspsms.com/sendSMS?username=DIPL_2015&message=".'Yor Refund OTP is '.$otp.'. Digital India Payments.'."&sendername=DIGPAY&smstype=TRANS&numbers=".$cust_phone_no."&apikey=8a841c94-76e8-4a8a-92ae-6bb4cdc04f0b", []);

    $response = \Unirest\Request::post("https://api.smsu.in/smpp/?username=digitalindia&password=YBJzuKP5C&from=DIGPAY&to=".$cust_phone_no."&text=".'Yor Refund OTP is '.$otp.'. Digital India Payments.'."", []);
   
    return Response::json(['code' => 2], 200);
  }
  public function getRefundOTPForm ($transaction_id)
  {
     $id = RefundOTP::where('transaction_id', $transaction_id)
     ->where('status', 1)
     ->pluck('id');

    if (!  $id) {
      return Response::json(['message' => 'Missing info'], 422);
    }
    return View::make('transactions.refund-otp-form')->with(['transaction_id'=>$transaction_id]);
  }
  public function postRefundOTP ($transaction_id)
  {
    //return Input::has('otp');
    if (! Input::has('otp') || ! $transaction_id) {
      return Response::json(['message' => 'Missing info'], 422);
    }
   
    $status = RefundOTP::getOTP(Input::get('otp'), $transaction_id);
    if (! $status) return Response::json(['code' => 1], 422);

    $transaction = AepsTransaction::where('id', $transaction_id)->where('refund_status', '1')->first();
    if (! $transaction) return Response::json(['code' => 404], 422);

    $transaction->refund_status=2;
    $transaction->save();

    $vendor = Vendor::where('user_id', $transaction->user_id)->lockForUpdate()->first();
     $vendor->balance += $transaction->amount;
      $vendor->save();
      $credit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 1, 'amount' =>  $transaction->amount, 'balance' => $vendor->balance,'activity' => 'credited', 'narration' =>'Refund Process- '.$transaction->id]);

      WalletAction::create([
        'user_id' => $vendor->user_id,
              'counterpart_id' => '',//Admin id
              'amount' => $transaction->amount,
              'status' => 1,
              'credit_id' => $credit->id,
              'wallet_request_id' => 0,
              'type' => 0,
              'admin' => false,
              'automatic' => false,
              'remarks' => 'Refund Process'
            ]);

      return Response::json(['code' => 2], 200);
  }
}