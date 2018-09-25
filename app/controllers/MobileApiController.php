<?php

use Acme\Auth\Auth;
use Carbon\Carbon;
use Acme\Helper\Rabbit;

class MobileApiController extends BaseController {

    public function getAgentDashboard ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      //@TODO: Check what the three fields mean - Ashwin
      $dashboard['withdraw'] = AepsTransaction::where('user_id', Auth::user()->id)
        ->where('type', 2)
        ->where('result', 1)
        ->where('status', 4)
        ->whereDate('created_at', '=', Carbon::today()->toDateString())
        ->sum('amount');
      $dashboard['withdraw'] = round($dashboard['withdraw'], 2);
      $dashboard['deposit'] = AepsTransaction::where('user_id', Auth::user()->id)
        ->where('type', 1)
        ->where('result', 1)
        ->where('status', 4)
        ->whereDate('created_at', '=', Carbon::today()->toDateString())
        ->sum('amount');
      $dashboard['deposit'] = round($dashboard['deposit'], 2);
      $dashboard['balance'] = Vendor::where('user_id', Auth::user()->id)->first()->balance;
      $dashboard['balance'] = round($dashboard['balance'], 2);

      return Response::json($dashboard, 200);
    }

    public function getTestAgentDashboard ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      //@TODO: Check what the three fields mean - Ashwin
      $dashboard['withdraw'] = AepsTransaction::where('user_id', Auth::user()->id)
        ->where('type', 2)
        ->where('result', 1)
        ->where('status', 4)
        ->whereDate('created_at', '=', Carbon::today()->toDateString())
        ->sum('amount');
      $dashboard['withdraw'] = round($dashboard['withdraw'], 2);

      $dashboard['withdraw_count_today'] = AepsTransaction::where('user_id', Auth::user()->id)
        ->whereRaw('date(created_at) = ?', [Carbon::today()])
        ->where('type', 2)
        ->where('result', 1)
        ->where('status', 4)
        ->count();
   $dashboard['deposit'] = AepsTransaction::where('user_id', Auth::user()->id)
        ->where('type', 1)
        ->where('result', 1)
        ->where('status', 4)
        ->whereDate('created_at', '=', Carbon::today()->toDateString())
        ->sum('amount');
      $dashboard['deposit'] = round($dashboard['deposit'], 2); 
      $dashboard['deposit_count_today'] = AepsTransaction::where('user_id', Auth::user()->id)
        ->whereRaw('date(created_at) = ?', [Carbon::today()])
        ->where('type', 1)
        ->where('result', 1)
        ->where('status', 4)
        ->count();
 $dashboard['balance_enquiry_count_today'] = AepsTransaction::where('user_id', Auth::user()->id)
        ->whereRaw('date(created_at) = ?', [Carbon::today()])
        ->where('type', 0)
        ->where('result', 1)
        ->where('status', 4)
        ->count();
 $dashboard['commission_today'] = AepsWalletAction::where('user_id', Auth::user()->id)
        ->where('commission', 1)
        ->whereRaw('date(created_at) = ?', [Carbon::today()])
        ->sum('amount');
      $dashboard['commission_today'] = round($dashboard['commission_today'], 2); 



 $dashboard['balance'] = Vendor::where('user_id', Auth::user()->id)->first()->balance;
      $dashboard['balance'] = round($dashboard['balance'], 2);


      // $dashboard['deposit_amount_weekly'] = AepsTransaction::where('user_id', Auth::user()->id)
      //   ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])
      //   ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])
      //   ->where('type', 1)
      //   ->where('result', 1)
      //   ->where('status', 4)
      //   ->sum('amount');
      // $dashboard['deposit_amount_weekly'] = round($dashboard['deposit_amount_weekly'], 2);  
      // $dashboard['deposit_count_weekly'] = AepsTransaction::where('user_id', Auth::user()->id)
      //   ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])
      //   ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])
      //   ->where('type', 1)
      //   ->where('result', 1)
      //   ->where('status', 4)
      //   ->count();
      // $dashboard['withdraw_amount_weekly'] = AepsTransaction::where('user_id', Auth::user()->id)
      //   ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])
      //   ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])
      //   ->where('type', 2)
      //   ->where('result', 1)
      //   ->where('status', 4)
      //   ->sum('amount');
      // $dashboard['withdraw_amount_weekly'] = round($dashboard['withdraw_amount_weekly'], 2);  
      // $dashboard['withdraw_count_weekly'] = AepsTransaction::where('user_id', Auth::user()->id)
      //   ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])
      //   ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])
      //   ->where('type', 2)
      //   ->where('result', 1)
      //   ->where('status', 4)
      //   ->count();
      // $dashboard['balance_enquiry_count_weekly'] = AepsTransaction::where('user_id', Auth::user()->id)
      //   ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])
      //   ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])
      //   ->where('type', 0)
      //   ->where('result', 1)
      //   ->where('status', 4)
      //   ->count();
      // $dashboard['deposit_amount_monthly'] = AepsTransaction::where('user_id', Auth::user()->id)
      //   ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
      //   ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
      //   ->where('type', 1)
      //   ->where('result', 1)
      //   ->where('status', 4)
      //   ->sum('amount');
      // $dashboard['deposit_amount_monthly'] = round($dashboard['deposit_amount_monthly'], 2);
      // $dashboard['deposit_count_monthly'] = AepsTransaction::where('user_id', Auth::user()->id)
      //   ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
      //   ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
      //   ->where('type', 1)
      //   ->where('result', 1)
      //   ->where('status', 4)
      //   ->count();
      // $dashboard['withdraw_amount_monthly'] = AepsTransaction::where('user_id', Auth::user()->id)
      //   ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
      //   ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
      //   ->where('type', 2)
      //   ->where('result', 1)
      //   ->where('status', 4)
      //   ->sum('amount');
      // $dashboard['withdraw_amount_monthly'] = round($dashboard['withdraw_amount_monthly'], 2);
      // $dashboard['withdraw_count_monthly'] = AepsTransaction::where('user_id', Auth::user()->id)
      //   ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
      //   ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
      //   ->where('type', 2)
      //   ->where('result', 1)
      //   ->where('status', 4)
      //   ->count();
      // $dashboard['balance_enquiry_count_monthly'] = AepsTransaction::where('user_id', Auth::user()->id)
      //   ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
      //   ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
      //   ->where('type', 0)
      //   ->where('result', 1)
      //   ->where('status', 4)
      //   ->count();
      
      // $dashboard['commission_weekly'] = AepsWalletAction::where('user_id', Auth::user()->id)
      //   ->where('commission', 1)
      //   ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])
      //   ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])
      //   ->sum('amount');
      // $dashboard['commission_weekly'] = round($dashboard['commission_weekly'], 2);
      // $dashboard['commission_monthly'] = AepsWalletAction::where('user_id', Auth::user()->id)
      //   ->where('commission', 1)
      //   ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
      //   ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
      //   ->sum('amount');
      // $dashboard['commission_monthly'] = round($dashboard['commission_monthly'], 2);    

     

      return Response::json($dashboard, 200);
    }    


    public function getDistributorDashboard ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $dashboard['totalNoOfAgents'] = Vendor::where('parent_id', Auth::user()->id)
        ->where('type', 1)
        ->count();
      $dashboard['balanceWithAgents'] = Vendor::where('parent_id', Auth::user()->id)
        ->where('type', 1)
        ->sum('balance');
      return Response::json($dashboard, 200);
    }

    public function getAgents ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $agents = Vendor::where('parent_id', Auth::user()->id)
        ->where('type', 1)
        -with('user')
        ->get();
      $response = array_map(function ($agent) {
        return (object) [
          'id' => $agent->id, // @TODO: WHat is agent id? Is it csr_id? It is id.
          'name' => $agent->user->name,
          'RBLId' => $agent->csr_id,
          'email' => $agent->user->email,
          'joiningDate' => Carbon::parse($agent->created_at)->format('d-m-Y'),
          'mobile' => $agent->user->phone_no,
          'balance' => round($agent->balance, 2),
          'distributor' => $agent->parent_id,
          'superDistributor' => Vendor::where('user_id', $agent->parent_id)->first()->parent_id
        ];
      }, $agents);
      return Response::json(['agents' => $response], 200);
    }

    public function postCreditAgentWallet ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $agent_code = Input::get('code'); // @TODO: Confirm what agentCode is. Assumed to be id. It is csr_id.
      $amount = Input::get('amount');

      $agentSubmitted = Vendor::where('user_id', $agent_code);
      if ($agentSubmitted->parent_id != Auth::user()->id)
        return Response::json('Agent not mapped to this Distributor', 400);

      $vendor = Vendor::where('user_id', $agentSubmitted->parent_id)->lockForUpdate()->first();
      if ($vendor->balance < $amount) return Response::json('Insufficient Funds', 420);

      $vendor->balance -= $amount;
      $vendor->save();
      $debit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 0, 'amount' => $amount, 'balance' => $vendor->balance]);
      // @TODO: Check all code for places where vendor->id was used instead of vendor->user_id
      $agent = Vendor::where('user_id', $agentSubmitted->id)->lockForUpdate()->first();
      $agent->balance += $amount;
      $agent->save();
      $credit = WalletTransaction::create(['user_id' => $agent->id, 'transaction_type' => 1, 'amount' => $amount, 'balance' => $agent->balance]);

      WalletAction::create([
        'user_id' => $agent->user_id,
        'counterpart_id' => $vendor->user_id,
        'amount' => $request->amount,
        'status' => 1,
        'credit_id' => $credit->id,
        'wallet_request_id' => $request->id,
        'type' => 0,
        'admin' => false,
        'automatic' => false
      ]);

      WalletAction::create([
        'user_id' => $vendor->user_id,
        'counterpart_id' => $agent->user_id,
        'amount' => $request->amount,
        'status' => 1,
        'debit_id' => $debit->id,
        'wallet_request_id' => $request->id,
        'type' => 0,
        'admin' => false,
        'automatic' => false
      ]);

      return Response::json('Success', 200);
    }

    public function postDebitAgentWallet ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $agent_code = Input::get('code'); // @TODO: Confirm what agentCode is. Assumed to be id. Its is csr_id.
      $amount = Input::get('amount');

      $agentSubmitted = Vendor::where('user_id', $agent_code);
      if ($agentSubmitted->parent_id != Auth::user()->id)
      return Response::json('Agent not mapped to this Distributor', 400);

      $agent = Vendor::where('user_id', $agentSubmitted->id)->lockForUpdate()->first();
      if ($agent->balance < $amount) return Response::json('Insufficient Funds', 420);

      $agent->balance -= $amount;
      $agent->save();
      $debit = WalletTransaction::create(['user_id' => $agent->user_id, 'transaction_type' => 0, 'amount' => $amount, 'balance' => $agent->balance]);
      // @TODO: Check all code for places where vendor->id was used instead of vendor->user_id
      $vendor = Vendor::where('user_id', $agent->parent_id)->lockForUpdate()->first();
      $vendor->balance += $amount;
      $vendor->save();
      $credit = WalletTransaction::create(['user_id' => $vendor->id, 'transaction_type' => 1, 'amount' => $amount, 'balance' => $vendor->balance]);

      WalletAction::create([
        'user_id' => $agent->user_id,
        'counterpart_id' => $vendor->user_id,
        'amount' => $request->amount,
        'status' => 1,
        'debit_id' => $debit->id,
        'wallet_request_id' => $request->id,
        'type' => 0,
        'admin' => false,
        'automatic' => false
      ]);

      WalletAction::create([
        'user_id' => $vendor->user_id,
        'counterpart_id' => $agent->user_id,
        'amount' => $request->amount,
        'status' => 1,
        'credit_id' => $credit->id,
        'wallet_request_id' => $request->id,
        'type' => 0,
        'admin' => false,
        'automatic' => false
      ]);

      return Response::json('Success', 200);
    }

    public function getTransactions () {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $offset = Input::get('offset');
      $limit = Input::has('limit') ? Input::get('limit') : 5; // If no limit is provided, return last 5 transactions
      $transactions = AepsTransaction::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->skip($offset)->take($limit)->get();
      $response = array_map(function ($transaction) {
        return (object) [
          'id' => $transaction->id,
          'aadharNo' => $transaction->aadhar_no,
          'serviceMod' => $this->getTransactionType($transaction->type)['type'],
          'bank' => Bank::find($transaction->bank_id)->iin,
          'amount' => round($transaction->amount, 2),
          'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'),
          'balance' => $transaction->balance,
          'status' => $transaction->result == 1 ? "Success" : ($transaction->result == 0 ? "Fail" : ''),
          'remarks' => '' //@TODO: Add remarks to aeps_transactions table
        ];
      }, json_decode($transactions));
      return Response::json(['transactions' => $response], 200);
    }

    public function getTransactionsByDistributor () {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $offset = Input::get('offset');
      $limit = Input::has('limit') ? Input::get('limit') : 5;
      $transactions = AepsTransaction::whereIn('user_id', Vendor::where('parent_id', Auth::user()->id)->lists('user_id'))
        ->orderBy('id', 'DESC')
        ->skip($offset)
        ->take($limit)
        ->get();
      $response = array_map(function ($transaction) {
        return (object) [
          'id' => $transaction->id,
          'aadharNo' => $transaction->aadhar_no,
          'serviceMod' => $this->getTransactionType($transaction->type)['type'],
          'bank' => Bank::find($transaction->bank_id)->iin, //@TODO: COnfirm if bank means bank code. It is bank_id.
          'amount' => round($transaction->amount, 2),
          'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'),
          'balance' => round($transaction->balance, 2),
          'status' => $transaction->result == 1 ? "Success" : ($transaction->result == 0 ? "Fail" : ''),
          'remarks' => '' //@TODO: Add remarks to aeps_transactions table
        ];
      }, json_decode($transactions));
      return Response::json(['transactions' => $response], 200);
    }

    public function getBanks ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $banks = Bank::WHERE('statusCode','=',1)->get();
      $response = array_map(function ($bank) {
        return (object) [
          'code' => $bank->iin,
          'bankName' => $bank->name
        ];
      }, json_decode($banks));
      return Response::json(['banks' => $response], 200);
    }

    public function postGenerateBalanceEnquiry ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      if (! Input::has('aadharNo') || ! Input::has('bankCode')) return Response::json("Invalid Parameters", 420);

      // if (! $this->limitTransactions(Input::get('aadharNo'), 0))
      //   return Response::json(['message' => 'Maximum number of Balance Enquiries for Aadhar number '.Input::get('aadharNo').' reached'], 422);

      $aadhar_no = Input::get('aadharNo');
      $bank_iin = Input::get('bankCode');
      $bank = Bank::where('iin', $bank_iin)->first();
      if ($bank == null) return Response::json("Invalid Parameters", 420);
      $transaction = AepsTransaction::create([
        'user_id' => Auth::user()->id,
        'aadhar_no' => $aadhar_no,
        'bank_id' => $bank->id,
        'type' => 0,
        'status' => 0,
        'stan' => incrementalHash(),
        'cust_name' =>Input::get('custName'),
        'cust_phone_no' =>Input::get('custPhoneNo')
      ]);
      return Response::json(['transactionId' => $transaction->id, 'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'), 'aadharNo' => $transaction->aadhar_no, 'bank' => $bank->iin], 200);
    }

    public function postGenerateDeposit ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      if (! Input::has('aadharNo') || ! Input::has('bankCode') || ! Input::has('amount')) return Response::json("Invalid Parameters", 420);

      // if (! $this->limitTransactions(Input::get('aadharNo'), 1))
      // return Response::json(['message' => 'Maximum number of Deposits for Aadhar number '.Input::get('aadharNo').' reached'], 422);

		if((Auth::user()->id == 1111 || Auth::user()->id == 1076 || Auth::user()->id == 1701 || Auth::user()->id == 703 || Auth::user()->id == 1819 || Auth::user()->id == 2090 || Auth::user()->id == 1418 || Auth::user()->id == 922 || Auth::user()->id == 1839 || Auth::user()->id == 1755  || Auth::user()->id == 582  || Auth::user()->id == 1579  || Auth::user()->id == 1314 || Auth::user()->id == 604 || Auth::user()->id == 618 || Auth::user()->id == 579 || Auth::user()->id == 819  || Auth::user()->id == 366 || Auth::user()->id == 760  || Auth::user()->id == 725  || Auth::user()->id == 1081 || Auth::user()->id == 1151 || Auth::user()->id == 1083 || Auth::user()->id == 713 || Auth::user()->id == 1378 || Auth::user()->id == 1036  || Auth::user()->id == 1318 || Auth::user()->id == 1930 || Auth::user()->id == 1919 || Auth::user()->id == 847 || Auth::user()->id == 1399 || Auth::user()->id == 1640 || Auth::user()->id == 885 || Auth::user()->id == 710 || Auth::user()->id == 865 || Auth::user()->id == 920 || Auth::user()->id == 534 || Auth::user()->id == 1862 || Auth::user()->id == 413 || Auth::user()->id == 647 || Auth::user()->id == 1464 || Auth::user()->id == 1315 || Auth::user()->id == 1338 || Auth::user()->id == 197 || Auth::user()->id == 1923 || Auth::user()->id == 794 || Auth::user()->id == 2349 || Auth::user()->id == 1499 || Auth::user()->id ==1506 || Auth::user()->id == 3863 || Auth::user()->id == 2108 || Auth::user()->id == 3751 || Auth::user()->id == 256 || Auth::user()->id == 3760|| Auth::user()->id == 1848 || Auth::user()->id == 2547  || Auth::user()->id == 3285 || Auth::user()->id == 3026 || Auth::user()->id ==5095 || 
                    Auth::user()->id ==5229  || Auth::user()->id ==5428 || Auth::user()->id ==5478 || Auth::user()->id ==5479 || Auth::user()->id ==5481 || Auth::user()->id ==5585  || Auth::user()->id ==4288 || Auth::user()->id ==5897  || Auth::user()->id ==5956 || Auth::user()->id ==6279 || Auth::user()->id ==6636 || Auth::user()->id ==7674   || Auth::user()->id ==8039 || Auth::user()->id ==8040 || Auth::user()->id ==8043 || Auth::user()->id ==8046 || Auth::user()->id ==8162  || Auth::user()->id ==9450 || Auth::user()->id ==2255 || Auth::user()->id ==10567|| Auth::user()->id==4424|| 
                      Auth::user()->id==4612  || Auth::user()->id==5384  || Auth::user()->id==5385  || Auth::user()->id==5858  || Auth::user()->id==5905 || Auth::user()->id==6815  || Auth::user()->id==7735  || Auth::user()->id==8358 || Auth::user()->id==5417 || Auth::user()->id==1822  || Auth::user()->id==4354 ))
			
        return Response::json(['message' => 'We apologize for the inconvenience and thank you for your patience. '], 422);
	
      $vendor = Vendor::where('user_id', Auth::user()->id)->first();
      if(Auth::user()->id==4 || Auth::user()->id==3)
         return Response::json(['message' => 'Temporarily Disabled'], 422);
      if ($vendor->balance < Input::get('amount'))
        return Response::json(['message' => 'Insufficient Balance'], 422);

      if (Input::get('amount') > 10000)
        return Response::json(['message' => 'Transaction Amount limit exceeded'], 422);          

      $aadhar_no = Input::get('aadharNo');
      $bank_iin = Input::get('bankCode');
      $amount = Input::get('amount');
      $bank = Bank::where('iin', $bank_iin)->first();
      if ($bank == null) return Response::json("Invalid Parameters", 420);
      $transaction = AepsTransaction::create([
        'user_id' => Auth::user()->id,
        'aadhar_no' => $aadhar_no,
        'bank_id' => $bank->id,
        'amount' => $amount,
        'type' => 1,
        'status' => 0,
        'stan' => incrementalHash(),
        'cust_name' =>Input::get('custName'),
        'cust_phone_no' =>Input::get('custPhoneNo')
      ]);
      return Response::json(['transactionId' => $transaction->id, 'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'), 'aadharNo' => $transaction->aadhar_no, 'bank' => $bank->iin, 'amount' => round($transaction->amount, 2)], 200);
    }

    public function postGenerateWithdraw ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      if (! Input::has('aadharNo') || ! Input::has('bankCode') || ! Input::has('amount')) return Response::json("Invalid Parameters", 420);

      // if (! $this->limitTransactions(Input::get('aadharNo'), 2))
      //   return Response::json(['message' => 'Maximum number of Withdrawals for Aadhar number '.Input::get('aadharNo').' reached'], 422);

      if (Input::get('amount') > 10000)
        return Response::json(['message' => 'Transaction Amount limit exceeded'], 422);

      $aadhar_no = Input::get('aadharNo');
      $bank_iin = Input::get('bankCode');
      $amount = Input::get('amount');
      $bank = Bank::where('iin', $bank_iin)->first();
      if ($bank == null) return Response::json("Invalid Parameters", 420);
      $transaction = AepsTransaction::create([
        'user_id' => Auth::user()->id,
        'aadhar_no' => $aadhar_no,
        'bank_id' => $bank->id,
        'amount' => $amount,
        'type' => 2,
        'status' => 0,
        'stan' => incrementalHash(),
        'cust_name' =>Input::get('custName'),
        'cust_phone_no' =>Input::get('custPhoneNo')
      ]);
      return Response::json(['transactionId' => $transaction->id, 'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'), 'aadharNo' => $transaction->aadhar_no, 'bank' => $bank->iin, 'amount' => round($transaction->amount, 2)], 200);
    }

    public function postConfirmTransaction ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      if (! Input::has('aadharNo') || ! Input::has('bankCode') || ! Input::has('fingerPrint') || ! Input::has('transactionId')) return Response::json("Invalid Parameters", 420);
      $aadhar_no = Input::get('aadharNo');
      $bank_iin = Input::get('bankCode');
      $fingerprint = Input::get('fingerPrint');
      $transaction = AepsTransaction::find(Input::get('transactionId'));
      $bank = Bank::where('iin', $bank_iin)->first();
      if (! $bank) return Response::json("Invalid Parameters", 420);
      if (! $transaction) return Response::json("Invalid Parameters", 420);
      if ($transaction->aadhar_no != $aadhar_no || $transaction->bank_id != $bank->id) return Response::json("Invalid Parameters", 420);

         $vendor = Vendor::where('user_id', Auth::user()->id)->first();
         if($vendor->device_service == 0)
         {
            $transactionPayload = ['aadhar_no' => $aadhar_no, 'bank_iin' => $bank->iin, 'fingerprint' => $fingerprint, 'amount' => $transaction->amount, 'stan' => $transaction->stan,'device_service' =>$vendor->device_service,'action' => $this->getAction($transaction->type)['type']];

         }
         if($vendor->device_service == 1)
         {
          $transactionPayload = ['aadhar_no' => $aadhar_no, 'bank_iin' => $bank->iin, 'mc'=>$fingerprint['mc'], 'udc'=>$fingerprint['udc'], 'dpId'=>$fingerprint['dpId'], 'rdsId'=>$fingerprint['rdsId'], 'rdsVer'=>$fingerprint['rdsVer'], 'dc'=>$fingerprint['dc'], 'mi'=>$fingerprint['mi'], 'pid'=>$fingerprint['pid'],'device_service' =>$vendor->device_service, 'amount' => $transaction->amount, 'stan' => $transaction->stan, 'action' => $this->getAction($transaction->type)['type']];

         }
     
      
     
      $transaction->status = $transaction->status == 0 ? 1 : $transaction->status;
      $transaction->save();
      if ($this->transact($transactionPayload, $vendor, $transaction->id)) {
        return Response::json([
          'transactionId' => $transaction->id,
          'aadharNo' => $transactionPayload['aadhar_no'],
        ], 200);
      }
      return Response::json("Transaction request to queue failed.", 500);
    }

    public function postTransactionStatus ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      if (! Input::has('transactionId')) return Response::json("Invalid Parameters", 420);
      $transaction = AepsTransaction::find(Input::get('transactionId'));
      if (! $transaction) return Response::json("Invalid Parameters", 420);
      $bank = Bank::where('id', $transaction->bank_id)->first();
      $response = AadhaarCode::where('response_code', $transaction->result_code)->first();
      if (! $response && $transaction->bank_response_code && $transaction->bank_response_code != '00')
        $response = 'Rejected at issuer bank: '.$transaction->bank_response_code;
      $remarks = $transaction->remarks;
      if ($transaction->remarks == 'queuefail') $remarks = 'Failed: Error';
      if ($transaction->status == 4) {
        return Response::json([
          'status' => 'Complete',
          'transactionId' => $transaction->id,
          'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'),
          'aadharNo' => $transaction->aadhar_no,
          'bank' => $bank->iin,
          'balance' => round($transaction->balance, 2),
          'response' => $response ? $response->description : $transaction->remarks,
          'remark' => '',
          'serviceMod' => $this->getTransactionType($transaction->type)['type']
        ], 200);
      }
      return Response::json(['status' => 'Pending'], 200);
    }

    public function getModeOfTransfer ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $modeOfTransferDict = Config::get('dictionary.MODE_OF_TRANSFER');
      $modes = [];
      foreach ($modeOfTransferDict as $key => $value) {
        array_push($modes, ['name' => $value, 'code' => $key]);
      }
      return Response::json(['modes' => $modes], 200);
    }

    public function postBalanceRequest ()
    {
      //@TODO: API expects no validation on server. Please change and validate.
      //@TODO: Check if value sent in request is bank_id or bank_iin
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $balanceRequestSubmitted = $this->filterOnly(Input::all(), ['bankId', 'amount', 'referenceNumber', 'bankBranch', 'modeOfTransfer']);
      $bank = Bank::where('id', $balanceRequestSubmitted['bankId'])->first();
      $balanceRequest = new BalanceRequest([
        'bank' => $bank->id,
        'amount' => $balanceRequestSubmitted['amount'],
        'transfer_mode' => $balanceRequestSubmitted['modeOfTransfer'],
        'branch' => Input::has('bankBranch') ? $balanceRequestSubmitted['bankBranch'] : '',
        'reference_number' => $balanceRequestSubmitted['referenceNumber'],
        'service_id' => 0,
        'user_id' => Auth::user()->id
      ]);
      $balanceRequest->save();
      return Response::json("Success", 200);
    }

    public function postBalanceRequestByDistributor ()
    {
      //@TODO: API expects no validation on server. Please change and validate.
      //@TODO: Check if value sent in request is bank_id or bank_iin
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $balanceRequestSubmitted = $this->filterOnly(Input::all(), ['bankId', 'amount', 'referenceNumber', 'bankBranch', 'modeOfTransfer']);
      $balanceRequest = new BalanceRequest([
        'bank' => $balanceRequestSubmitted['bankId'],
        'amount' => $balanceRequestSubmitted['amount'],
        'transfer_mode' => $balanceRequestSubmitted['modeOfTransfer'],
        'branch' => $balanceRequestSubmitted['bankBranch'],
        'reference_number' => $balanceRequestSubmitted['referenceNumber'],
        'service_id' => 0,
        'user_id' => Auth::user()->id
      ]);
      $balanceRequest->save();
      return Response::json("Success", 200);
    }

    public function getBankAccountInfo ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      return Response::json(["bankAccounts" => [
        (object) [
          'accountName' => 'DIGITAL INDIA PAYMENTS LIMITED',
          'accountNo' => '039305008196',
          'ifscCode' => 'ICIC0000393',
          'bankName' => 'ICICI BANK LIMITED',
          'branch' => 'CIBD MUMBAI BRANCH',
          'bankId' => 7
          ]
        ]
      ]);
    }

    public function getWalletReports ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $offset = Input::get('offset');
      $limit = Input::has('limit') ? Input::get('limit') : 5;
      $aeps_wallet_actions = AepsWalletAction::where('user_id', Auth::user()->id)
        ->orderBy('id', 'DESC')
        ->skip($offset)
        ->take($limit)
    		->with('debit')
    		->with('credit')
        ->with('transaction')
    		->get();
      $response = array_map(function ($action) {
        return (object) [
          'id' => $action->transaction->id,
          'aadharNo' => $action->transaction->aadhar_no,
          'serviceMod' => $this->getTransactionType($action->transaction_type)['type'],
          'amount' => round($action->amount, 2),
          'timestamp' => Carbon::parse($action->transaction->created_at)->format('d-m-Y H:i:s'),
          'balance' => round($action->transaction->balance, 2),
          'fees' => '',
          'credit' => isset($action->credit->id) ? round($action->credit->amount, 2) : '',
          'debit' => isset($action->debit->id) ? round($action->debit->amount, 2) : ''
        ];
      }, json_decode($aeps_wallet_actions));
      return Response::json(['transaction' => $response], 200);
    }

    public function getWalletReportsForDistributor ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $offset = Input::get('offset');
      $limit = Input::has('limit') ? Input::get('limit') : 5;
      $aeps_wallet_actions = AepsWalletAction::whereIn('user_id', Vendor::where('parent_id', Auth::user()->id)->lists('user_id'))
        ->orderBy('id', 'DESC')
        ->skip($offset)
        ->take($limit)
    		->with('debit')
    		->with('credit')
        ->with('transaction')
    		->get();
      $response = array_map(function ($action) {
        return (object) [
          'id' => $action->transaction->id,
          // 'adharNo' => $action->transaction->aadhar_no,
          'serviceMod' => $this->getTransactionType($action->transaction_type)['type'],
          'amount' => round($action->amount, 2),
          'timestamp' => Carbon::parse($action->transaction->created_at)->format('d-m-Y H:i:s'),
          'balance' => round($action->transaction->balance, 2),
          'credit' => isset($action->credit->id) ? round($action->credit->amount, 2) : '',
          'debit' => isset($action->debit->id) ? round($action->debit->amount, 2) : ''
        ];
      }, json_decode($aeps_wallet_actions));
      return Response::json(['transaction' => $response], 200);
    }

    public function getBalanceRequests ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $offset = Input::get('offset');
      $limit = Input::has('limit') ? Input::get('limit') : 5;
      $requests = BalanceRequest::where('user_id', Auth::user()->id)
        ->orderBy('id', 'DESC')
        ->skip($offset)
        ->take($limit)
        ->get();
      $response = array_map(function ($request) {
        return (object) [
          'id' => $request->id,
          'modeOfTransfer' => Config::get('dictionary.MODE_OF_TRANSFER')[$request->transfer_mode],
          'status' => $this->getRequestStatusByKey($request->status),
          'amount' => round($request->amount, 2),
          'timestamp' => Carbon::parse($request->created_at)->format('d-m-Y H:i:s'),
          'bank' => Bank::find($request->bank)->name,
          'branch' => $request->branch, //@TODO: Branch is not compulsory. Verify it doesn't break any code.
          'referenceNo' => $request->reference_number
        ];
      }, json_decode($requests));
      return Response::json(['report' => $response], 200);
    }

    public function getBalanceRequestsByDistributor ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $offset = Input::get('offset');
      $limit = Input::has('limit') ? Input::get('limit') : 5;
      $requests = BalanceRequest::where('user_id', Auth::user()->id)
        ->orderBy('id', 'DESC')
        ->skip($offset)
        ->take($limit)
        ->get();
      $response = array_map(function ($request) {
        return (object) [
          'id' => $request->id,
          'modeOfTransfer' => Config::get('dictionary.MODE_OF_TRANSFER')[$request->transfer_mode],
          'status' => $this->getRequestStatusByKey($request->status),
          'amount' => round($request->amount, 2),
          'timestamp' => Carbon::parse($request->created_at)->format('d-m-Y H:i:s'),
          'bank' => Bank::find($request->bank)->name,
          'branch' => $request->branch, //@TODO: Branch is not compulsory. Verify it doesn't break any code.
          'referenceNo' => $request->reference_number
        ];
      }, json_decode($requests));
      return Response::json(['report' => $response], 200);
    }

    public function postTransactionDetails ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $transaction_id = Input::only('transactionId');
      $transaction = AepsTransaction::where('id', $transaction_id)
        ->with('bank')
        ->with('user')
        ->with('user.vendor')
        ->first();
      if (! $transaction) return Response::json('Invalid Parameter', 420);
      $response = AadhaarCode::where('response_code', $transaction->result_code)->first();
      return Response::json([
        'transactionId' => $transaction->id,
        'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'),
        'aadhaarNo' => $transaction->aadhar_no,
        'bankName' => $transaction->bank->name,
        'bankCode' => $transaction->bank->iin,
        'balance' => round($transaction->balance, 2),
        'response' => $response ? $response->description : '',
        'remark' => '',
        'serviceMod' => $this->getTransactionType($transaction->type)['type'],
        'terminalId' => $transaction->user->vendor->terminal_id,
        'agentId' => $transaction->user->vendor->csr_id,
        'BCName' => $transaction->user->name,
        'mATNreqID' => $transaction->user->vendor->device_id,
        'amount' => round($transaction->amount, 2)
      ], 200);
    }

    public function getValidateTerminalId ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $terminal_id = Input::header('terminalId');
      if(Auth::user()->vendorDetails->fingerprint_device_id == 4)
      {
        return Response::json("Success", 200);
      }else
      {
         if (Auth::user()->vendorDetails->device_sr_no == $terminal_id)
        return Response::json("Success", 200);
      return Response::json('Invalid Terminal', 400);
      }
     
    }

   public function getValidateDCcode ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $dccode= Input::header('terminalId');
  
      if(Auth::user()->vendorDetails->dccode === NULL || Auth::user()->vendorDetails->dccode == '')
       {
          $users=Vendor::where('user_id',Auth::user()->vendorDetails->user_id)->first();
          $users->dccode=$dccode;
          $users->save();
        return Response::json("Success", 200);

       }else
       {
        if (Auth::user()->vendorDetails->dccode == $dccode)
        return Response::json("Success", 200);
        return Response::json('Invalid RD Terminal', 400);
       } 
       
      
    }


    public function getContactDetails ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      // @TODO: Get the following fields.
      return Response::json([
        'phone' => 1,
        'location' => [
          'addressLine1' => 1,
          'addressLine2' => 2,
          'addressLine3' => 3,
          'state' => 4,
          'city' => 5,
          'country' => 6,
          'zipcode' => 7
        ],
        'email' => 2,
        'web' => 3
      ], 200);
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

    private function getStatusByKey ($key)
    {
      return $key == 0 ? "Fail" : ($key == 1 ? "Success" : '');
    }

    private function getRequestStatusByKey ($key)
    {
      return $key == 0 ? "Pending" : ($key == 1 ? "Success" : ($key == 2 ? "Fail" : ''));
    }


    private function getTransactionType ($id) {
      $transactionDict = [
        0 => [
          'id' => 0,
          'type' => 'Balance Enquiry'
        ],
        1 => [
          'id' => 1,
          'type' => 'Deposit'
        ],
        2 => [
          'id' => 2,
          'type' => 'Withdraw'
        ]
      ];
      return $transactionDict[$id];
    }

    private function getAction ($id) {
      $transactionDict = [
        0 => [
          'id' => 0,
          'type' => 'balance_enquiry'
        ],
        1 => [
          'id' => 1,
          'type' => 'deposit'
        ],
        2 => [
          'id' => 2,
          'type' => 'withdraw'
        ]
      ];
      return $transactionDict[$id];
    }

    //@TODO: Implement limit of 5 successful transactions on an Aadhar Number on Mobile APIs.
    private function limitTransactions ($aadhar_no, $transaction_type) {
      return AepsTransaction::where('aadhar_no', $aadhar_no)
        ->where('type', $transaction_type)
        ->where('status', '>', 0)
        ->whereDate('created_at', '=', Carbon::today()->toDateString())
        ->count() < 5 ? true : false;
    }


   public function createPlayStoreUsers()
    {
      
     

      $validator = Validator::make(Input::all(), [
      'name' => 'required',
      'mobile_no' => 'required|integer',
      'state' => 'required'
      
       ]);
      if ($validator->fails())
      return Response::json($validator->messages(), 422);

      $name = Input::get('name');
      $mobile_no = Input::get('mobile_no');
      $state = Input::get('state');

      $mobileChecker = User::where('phone_no','=',$mobile_no)->first();

      if($mobileChecker)
      {

      return Response::json('Already Register with Us.', 422);
      }
      else
      {
        $playstoreusers = Playstoreusers::where('mobile_no',$mobile_no)->first();

        if($playstoreusers)
        {

        	return Response::json('Already Register with Us.', 422);

        }else
        {


         $playstoreuser= new Playstoreusers();
         $playstoreuser->name = $name;
         $playstoreuser->mobile_no = $mobile_no;
         $playstoreuser->state =$state;
         $playstoreuser->save();

          if($playstoreuser)
          {
          return Response::json('success', 200);
          }else
          {
          return Response::json('error', 422);
          }




        } 
        
      }  

    }

public function getrddeviceinfo()
{
  if (! Auth::user()) return Response::json("Invalid Token", 400);
    $device_info=Vendor::where('user_id', Auth::user()->id)->first();
    if($device_info)
    {
      if($device_info->fingerprint_device_id==1)
      {
        $deviceInfo=DeviceInfo::where('device_id', 1)->first();
        $device_name=$deviceInfo->name;
        $rdService=array('packageName'=>$deviceInfo->rd_service_package,'isNecessary'=>'true','link'=>$deviceInfo->rd_service_link);
        $managementClient=array('packageName'=>$deviceInfo->management_client_package,'isNecessary'=>'true','link'=>$deviceInfo->management_client_link);
        /*$device_name='Morpho';
        $rdService=array('packageName'=>'com.mantra.rdservice','isNecessary'=>'true','link'=>'https://play.google.com/store/apps/details?id=com.mantra.clientmanagement');
        $managementClient=array('packageName'=>'com.mantra.managementClient','isNecessary'=>'true','link'=>'https://play.google.com/store/apps/details?id=com.mantra.clientmanagement');*/

      }
      if($device_info->fingerprint_device_id==2)
      {
        $deviceInfo=DeviceInfo::where('device_id', 2)->first();
        $device_name=$deviceInfo->name;
        $rdService=array('packageName'=>$deviceInfo->rd_service_package,'isNecessary'=>'true','link'=>$deviceInfo->rd_service_link);
        $managementClient=array('packageName'=>$deviceInfo->management_client_package,'isNecessary'=>'true','link'=>$deviceInfo->management_client_link);
        /*$device_name='Mantra';
        $rdService=array('packageName'=>'com.mantra.rdservice','isNecessary'=>'true','link'=>'https://play.google.com/store/apps/details?id=com.mantra.clientmanagement');
        $managementClient=array('packageName'=>'com.mantra.managementClient','isNecessary'=>'true','link'=>'https://play.google.com/store/apps/details?id=com.mantra.clientmanagement');*/

      }

       if($device_info->fingerprint_device_id==5)
      {
        $deviceInfo=DeviceInfo::where('device_id', 5)->first();
        $device_name=$deviceInfo->name;
        $rdService=array('packageName'=>$deviceInfo->rd_service_package,'isNecessary'=>'true','link'=>$deviceInfo->rd_service_link);
        $managementClient=array('packageName'=>$deviceInfo->management_client_package,'isNecessary'=>'true','link'=>$deviceInfo->management_client_link);
        /*$device_name='Mantra';
        $rdService=array('packageName'=>'com.mantra.rdservice','isNecessary'=>'true','link'=>'https://play.google.com/store/apps/details?id=com.mantra.clientmanagement');
        $managementClient=array('packageName'=>'com.mantra.managementClient','isNecessary'=>'true','link'=>'https://play.google.com/store/apps/details?id=com.mantra.clientmanagement');*/

      }

       if($device_info->fingerprint_device_id==3)
      {
        $deviceInfo=DeviceInfo::where('device_id', 3)->first();
        $device_name=$deviceInfo->name;
        $rdService=array('packageName'=>$deviceInfo->rd_service_package,'isNecessary'=>'true','link'=>$deviceInfo->rd_service_link);
        $managementClient=array('packageName'=>$deviceInfo->management_client_package,'isNecessary'=>'true','link'=>$deviceInfo->management_client_link);
        

      }

       if($device_info->fingerprint_device_id==6)
      {
        $deviceInfo=DeviceInfo::where('device_id', 6)->first();
        $device_name=$deviceInfo->name;
        $rdService=array('packageName'=>$deviceInfo->rd_service_package,'isNecessary'=>'true','link'=>$deviceInfo->rd_service_link);
        $managementClient=array('packageName'=>$deviceInfo->management_client_package,'isNecessary'=>'true','link'=>$deviceInfo->management_client_link);
        

      }

      $device_infoarray=array();
      $device_infoarray=array(
        'name'=>$device_name,'deviceId'=>$device_info->device_sr_no,'rdService'=>$rdService,'managementClient'=>$managementClient
      );

      $arraydata=array('devices'=> [$device_infoarray]);

        return Response::json($arraydata, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
  }else
  {
    return Response::json('error', 422);
  }
}

/* functions to settlement api*/
  public function getSettlementStatus ()
  {

    if (! Auth::user()) return Response::json("Invalid Token", 444);
    
    $user_balance=Vendor::where('user_id',Auth::user()->id)->pluck('balance');
    $closing_balance=ClosingBalance::where('user_id',Auth::user()->id)->where('settlement_status',0)->latest()->pluck('balance');

    if($closing_balance==0)
   // return '"You have submitted request alredy for today"';
      return Response::json('You have already submitted request for today', 400);

    $response = [
        'available_balance' => $user_balance,
        'closing_balance' => $closing_balance
      ];
    return Response::json(['status'=>'success','response' => $response], 200);
  }
  public function getSettlement ()
  {
    if (! Auth::user()) return Response::json("Invalid Token", 444);
    
    $data = Settlement::where('settlement_request.user_id','=',Auth::user()->id)
 
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
  ->get();
     $response = array_map(function ($data) {
        return (object) [
          'name' => $data->name,
          'settlement_request' => $data->status,
          'beneficiary_name'=>$data->beneficiary_name,
          'account_number'=>$data->account_number,
          'transaction_amount'=>$data->amount,
          'created_at'=>$data->created_at,
          'request_id'=>$data->id
        ];
      }, json_decode($data));
    return Response::json(['status'=>'success','response' => $response], 200);
  }

public function postSettlementRequest ()
  {
    if (! Auth::user()) return Response::json("Invalid Token", 400);
      
    
    $transaction_amount=Input::get('transaction_amount');

    if((Auth::user()->id == 1111 || Auth::user()->id == 1076 || Auth::user()->id == 1701 || Auth::user()->id == 703 || Auth::user()->id == 1819 || Auth::user()->id == 2090 || Auth::user()->id == 1418 || Auth::user()->id == 922 || Auth::user()->id == 1839 || Auth::user()->id == 1755  || Auth::user()->id == 582  || Auth::user()->id == 1579  || Auth::user()->id == 1314 || Auth::user()->id == 604 || Auth::user()->id == 618 || Auth::user()->id == 579 || Auth::user()->id == 819  || Auth::user()->id == 366 || Auth::user()->id == 760  || Auth::user()->id == 725  || Auth::user()->id == 1081 || Auth::user()->id == 1151 || Auth::user()->id == 1083 || Auth::user()->id == 713 || Auth::user()->id == 1378 || Auth::user()->id == 1036  || Auth::user()->id == 1318 || Auth::user()->id == 1930 || Auth::user()->id == 1919 || Auth::user()->id == 847 || Auth::user()->id == 1399 || Auth::user()->id == 1640 || Auth::user()->id == 885 || Auth::user()->id == 710 || Auth::user()->id == 865 || Auth::user()->id == 920 || Auth::user()->id == 534 || Auth::user()->id == 1862 || Auth::user()->id == 413 || Auth::user()->id == 647 || Auth::user()->id == 1464 || Auth::user()->id == 1315 || Auth::user()->id == 1338 || Auth::user()->id == 197 || Auth::user()->id == 1923 || Auth::user()->id == 794 || Auth::user()->id == 2349 || Auth::user()->id == 1499 || Auth::user()->id ==1506 || Auth::user()->id == 3863 || Auth::user()->id == 2108 || Auth::user()->id == 3751 || Auth::user()->id == 256 || Auth::user()->id == 3760|| Auth::user()->id == 1848 || Auth::user()->id == 2547  || Auth::user()->id == 3285 || Auth::user()->id == 3026 || Auth::user()->id ==5095 || 
                    Auth::user()->id ==5229  || Auth::user()->id ==5428 || Auth::user()->id ==5478 || Auth::user()->id ==5479 || Auth::user()->id ==5481 || Auth::user()->id ==5585  || Auth::user()->id ==4288 || Auth::user()->id ==5897  || Auth::user()->id ==5956 || Auth::user()->id ==6279 || Auth::user()->id ==6636 || Auth::user()->id ==7674   || Auth::user()->id ==8039 || Auth::user()->id ==8040 || Auth::user()->id ==8043 || Auth::user()->id ==8046 || Auth::user()->id ==8162  || Auth::user()->id ==9450 || Auth::user()->id ==2255 || Auth::user()->id ==10567  || Auth::user()->id==4424  || Auth::user()->id==4612  || Auth::user()->id==5384  || Auth::user()->id==5385  || Auth::user()->id==5858  || Auth::user()->id==5905  || Auth::user()->id==6815  || Auth::user()->id==7735  || Auth::user()->id==8358  || Auth::user()->id==5417 || Auth::user()->id==3349 || Auth::user()->id==5984 || Auth::user()->id==5985 || Auth::user()->id==5986 || Auth::user()->id==5987 || 
Auth::user()->id==5988 || Auth::user()->id==5989 || Auth::user()->id==5990 || Auth::user()->id==5991 || Auth::user()->id==5992 || 
Auth::user()->id==5993 || Auth::user()->id==5994 || Auth::user()->id==6009 || Auth::user()->id==6010 || Auth::user()->id==6011 ||
 Auth::user()->id==6012 || Auth::user()->id==6013 || Auth::user()->id==6014 || Auth::user()->id==6015 || Auth::user()->id==6016 || 
 Auth::user()->id==6017 || Auth::user()->id==6018 || Auth::user()->id==6024 || Auth::user()->id==6027 || Auth::user()->id==6028 || 
 Auth::user()->id==6029 || Auth::user()->id==6030 || Auth::user()->id==6039 || Auth::user()->id==6040 || Auth::user()->id==6041 ||
 Auth::user()->id==6042 || Auth::user()->id==6067 || Auth::user()->id==6068 || Auth::user()->id==6069 || Auth::user()->id==6070 || 
 Auth::user()->id==6071 || Auth::user()->id==6072 || Auth::user()->id==6073 || Auth::user()->id==6074 || Auth::user()->id==6075 ||
 Auth::user()->id==6076 || Auth::user()->id==6077 || Auth::user()->id==6078 || Auth::user()->id==6079 || Auth::user()->id==6080 ||
 Auth::user()->id==6081 || Auth::user()->id==6082 || Auth::user()->id==6083 || Auth::user()->id==6084 || Auth::user()->id==6085 ||
 Auth::user()->id==6086 || Auth::user()->id==6087 || Auth::user()->id==6088 || Auth::user()->id==6089 || Auth::user()->id==6090 || 
 Auth::user()->id==6091 || Auth::user()->id==6092 || Auth::user()->id==6093 || Auth::user()->id==6094 || Auth::user()->id==6095 || 
 Auth::user()->id==6096 || Auth::user()->id==6097 || Auth::user()->id==6098 || Auth::user()->id==6099 || Auth::user()->id==6100 || 
 Auth::user()->id==6101 || Auth::user()->id==6102 || Auth::user()->id==6103 || Auth::user()->id==6104 || Auth::user()->id==6105 || 
 Auth::user()->id==6106 || Auth::user()->id==6107 || Auth::user()->id==6108 || Auth::user()->id==6109 || Auth::user()->id==6110 || 
 Auth::user()->id==6111 || Auth::user()->id==6112 || Auth::user()->id==6113 || Auth::user()->id==6114 || Auth::user()->id==6115 ||
 Auth::user()->id==6116 || Auth::user()->id==6117 || Auth::user()->id==6118 || Auth::user()->id==6119 || Auth::user()->id==6120 || 
Auth::user()->id==6121 || Auth::user()->id==6122 || Auth::user()->id==6123 || Auth::user()->id== 6124 || Auth::user()->id== 6125 || Auth::user()->id== 1822  || Auth::user()->id==4354))
			
        return Response::json(['message' => 'We apologize for the inconvenience and thank you for your patience. '], 422);
        
    
    $settlement_bank_id=Settlement::where('user_id',Auth::user()->id)->latest()->pluck('id');
    if (!$settlement_bank_id) 
    return Response::json('Settlement details not found', 400);
    
    $closing_balance = ClosingBalance::where('user_id',Auth::user()->id)->latest()->pluck('balance');
  
    if ($closing_balance < $transaction_amount) return Response::json('Closing Balance is not enough', 400);
    $vendor = Vendor::where('user_id', Auth::user()->id)->lockForUpdate()->first();
    if ($vendor->balance < $transaction_amount) return Response::json('Current balance is not enough', 400);

    ClosingBalance::where('user_id',Auth::user()->id)->update(['settlement_status'=>1]);

    $vendor->balance -= $transaction_amount;
    
    $debit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 0, 'amount' =>  $transaction_amount, 'balance' => $vendor->balance,'activity' => 'debit', 'narration' =>'Settlement-request']);

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

    $settlement_status=SettlementRequest::create([
      'user_id'=>Auth::user()->id,
      'transaction_amount'=>$transaction_amount,
      'remaining_amount'=> $vendor->balance,
      'settlement_bank_id'=>$settlement_bank_id,
      'transaction_id'=>$debit->id,
      'settlement_bank_id'=>$settlement_bank_id,
      'created_at'=>date("Y-m-d H:i:s")
    ]);
    
    $vendor->save();
    return Response::json("", 200);
  }
  public function getSettlementAccount ()
  {
    $response = Settlement::where('user_id', Auth::user()->id)->latest()->first();
    //$response = Settlement::where('user_id', Auth::user()->id)->latest()->first();
    //dd($response);
    if($response)
    {
       $arraydata=array("id"=>$response->id,"user_id"=>$response->user_id,"beneficiary_name"=>$response->beneficiary_name,"bank_name"=>$response->bank_name,"account_number"=>$response->account_number,"ifsc_code"=>$response->ifsc_code,"settlement_type"=>$response->settlement_type,'isExist'=>"true");
     return Response::json(['status'=>'success','response' => $arraydata], 200);
    }else
    {
       $arraydata=array('isExist'=>"false");
      return Response::json(['status'=>'success','response' => $arraydata], 200);
    }

  }
public function postSettlementDetails ()
{ 

  $beneficiary_name=Input::get('beneficiary_name');
  $account_number=Input::get('account_number');
  $ifsc_code=Input::get('ifsc_code');
  $bank_name=Input::get('bank_name');

  $settlement_type=0;//Input::get('settlement_type');

  $settlement_status=Settlement::create([
    'user_id'=>Auth::user()->id,
    'beneficiary_name'=>$beneficiary_name,
    'account_number'=>$account_number,
    'ifsc_code'=>$ifsc_code,
    'bank_name'=>$bank_name,
    'settlement_type'=>$settlement_type,
    'created_at'=>date("Y-m-d H:i:s")
  ]);
  
  return Response::json("Sucess", 200);
}

public function postDeviceDetails ()
{ 

  $deviceMake=Input::get('deviceMake');
  $deviceModel=Input::get('deviceModel');
  $deviceSerial=Input::get('deviceSerial');
  
  $validator = Validator::make(array(
    'device_make'=>$deviceMake,
    'device_model'=>$deviceModel,
    'device_serial'=>$deviceSerial
  ), array(
    'device_make' => 'required',
    'device_model' => 'required',
    'device_serial' => 'required'
  ));
  if ($validator->fails())
    return Response::json('Important data is missing', 400);

  $data = DeviceDetails::where('user_id',Auth::user()->id)->pluck('user_id');

  if ($data)
  return Response::json('', 200);

  $device_data=array(
      'user_id'=>Auth::user()->id,
      'device_make'=>$deviceMake,
      'device_model'=>$deviceModel,
      'device_serial'=>$deviceSerial,  
      'created_at'=>date("Y-m-d H:i:s") 
        );
    $device = new DeviceDetails();
    $device->insert($device_data);
  return Response::json('', 200);
}

//get location api

public function getlocation()
{
if (! Auth::user()) return Response::json("Invalid Token", 400);

  $versionCode=Input::get('versionCode');
  $manufacturerName=Input::get('manufacturerName');
  $modelNumber=Input::get('modelNumber');
  $modelId=Input::get('modelId');
  $BuildVersion=Input::get('BuildVersion');
  $city=Input::get('city');
  $state=Input::get('state');
  $BuildVersion=Input::get('BuildVersion');
  $lat=Input::get('lat');
  $long=Input::get('long');

  

  $MobileUserlocations=MobileUserlocation::create([
    'user_id'=>Auth::user()->id,
    'versionCode'=>$versionCode,
    'manufacturerName'=>$manufacturerName,
    'modelNumber'=>$modelNumber,
    'modelId'=>$modelId,
    'BuildVersion'=>$BuildVersion,
    'city'=>$city,
    'state'=>$state,
    'BuildVersion'=>$BuildVersion,
    'lat'=>$lat,
    'long'=>$long

  ]);
  
  return Response::json("Sucess", 200);



}









}

?>
