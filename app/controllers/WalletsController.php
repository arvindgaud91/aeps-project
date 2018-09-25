<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;
use Acme\Helper\Export;

class WalletsController extends BaseController
{

  // @TODO set up transactions for atomicity

  public function getBalanceRequest()
  {
    if (! Auth::user()) return Redirect::to('/');
    return View::make('wallets.balance-request');
  }

  public function postBalanceRequest()
  {
    $validator = Validator::make(Input::all(), [
      'amount' => 'required|integer',
      'transfer_mode' => 'required|integer',
      'user_id' => 'required|integer',
      'bank' => 'required|integer',
      'reference_number' => 'required'
    ]);
    if ($validator->fails())
      return Response::json($validator->messages(), 500);
    $balanceRequestSubmitted = $this->filterOnly(Input::all(), ['amount', 'transfer_mode', 'service_id', 'bank', 'branch', 'reference_number', 'user_id']);
    $balanceRequest = new BalanceRequest($balanceRequestSubmitted);
    $balanceRequest->save();
    return $balanceRequest;
  }

  public function getBalanceRequestFromDistributor ()
  {
    GateKeeper::checkRoles(Auth::user(), 1);
    $parent = User::find(Auth::user()->vendorDetails->parent_id);
    return View::make('wallets.from-distributor')
      ->withParent($parent);
  }

  public function getBalanceRequestFromSuperDistributor ()
  {
    GateKeeper::checkRoles(Auth::user(), 2);
    $parent = User::find(Auth::user()->vendorDetails->parent_id);
    return View::make('wallets.from-super-distributor')
      ->withParent($parent);
  }

  public function getIncomingBalanceRequestByParentVendor ($id)
  {
    GateKeeper::checkRoles(Auth::user(), [2, 3]);
    $data['requests'] = BalanceRequestVendor::where('parent_id', $id)->where('status', 0)->with('user')->with('user.vendorDetails')->get();
    return View::make('wallets.incoming-request')->with($data);
  }
  public function export_wallet_request_data($id){
    $mode_of_transfer=Config::get('dictionary.MODE_OF_TRANSFER');
    $records = BalanceRequestVendor::where('parent_id', $id)->whereIn('wallet_balance_vendor_requests.status', [0,1,2])->join('users','wallet_balance_vendor_requests.user_id','=','users.id')->select('users.name as NAME','wallet_balance_vendor_requests.amount as AMOUNT','wallet_balance_vendor_requests.remarks as REMARKS','wallet_balance_vendor_requests.created_at as REQUESTED_AT',DB::raw("
          (CASE 
          WHEN wallet_balance_vendor_requests.status=0 THEN 'Pending' 
          WHEN wallet_balance_vendor_requests.status=1 THEN 'Accepted'
          WHEN wallet_balance_vendor_requests.status=2 THEN 'Rejected' END) as Status"))->get();
    //dd($records);
    $export_csv= new Export();
        $export_csv->exportData($records,"wallets-request-report-");
  }
  public function getIncomingBalanceRequestsByStatus ($id)
  {
    return BalanceRequestVendor::where('parent_id', $id)->where('status', $this->getIncomingBalanceRequestStatus(Input::get('status')))->with('user')->get();
  }

  private function getIncomingBalanceRequestStatus ($status)
  {
    $dict = [
      "pending" => 0,
      "approved" => 1,
      "rejected" => 2
    ];
    return $dict[$status];
  }

  public function getCreditWalletRequest ($id)
  {
    GateKeeper::checkRoles(Auth::user(), [2, 3]);
    $data['child'] = User::where('id', $id)->with('vendorDetails')->first();
    $data['vendorType'] = Vendor::type(Vendor::where('user_id', $data['child']->vendorDetails->parent_id)->first()->type);
    return View::make('wallets.credit-request')->with($data);
  }

  public function getDebitWalletRequest ($id)
  {
    GateKeeper::checkRoles(Auth::user(), [2, 3]);
    $data['child'] = User::where('id', $id)->with('vendorDetails')->first();
    $data['vendorType'] = Vendor::type(Vendor::where('user_id', $data['child']->vendorDetails->parent_id)->first()->type);
    return View::make('wallets.debit-request')->with($data);
  }

  public function getTransferRequest($id)
  {
    GateKeeper::checkRoles(Auth::user(), [2, 3]);
    $data['requests'] = BalanceRequestVendor::where('parent_id', $id)->where('status', 0)->with('user')->with('user.vendorDetails')->get();
    return View::make('wallets.incoming-request')->with($data);
  }

  public function postApproveBalanceRequestByDistributor ($id)
  {
    GateKeeper::checkRoles(Auth::user(), 2, true);

    $request = BalanceRequestVendor::find($id);
//dd($request);
    if (Vendor::where('user_id', $request->user_id)->first()->parent_id != Auth::user()->id)
      return Response::json(['code' => 2], 403);

    $vendor = Vendor::where('user_id', $request->parent_id)->lockForUpdate()->first();
    if ($vendor->balance < $request->amount) return Response::json(['code' => 1], 422);

    $vendor->balance -= $request->amount;
    $vendor->save();
    $debit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 0,'activity'=>'Debit-Request','narration'=>'Transferred To-'.User::where('id',$request->user_id)->pluck('name'), 'amount' => $request->amount, 'balance' => $vendor->balance]);

    $agent = Vendor::where('user_id', $request->user_id)->lockForUpdate()->first();
    $agent->balance += $request->amount;
    $agent->save();
    $credit = WalletTransaction::create(['user_id' => $agent->user_id, 'transaction_type' => 1,'activity'=>'Credit-Request','narration'=>'Received From-'.Auth::user()->name,'amount' => $request->amount, 'balance' => $agent->balance]);

    $request->status = 1;
    $request->save();

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
    Event::fire('vendorBalanceRequest:committed', [['request_id' => $request->id, 'user_id' => Auth::user()->id, 'type' => 3, 'status' => 1]]);

    return Response::json($request, 200);
  }

  public function postRejectBalanceRequestByDistributor ($id)
  {
    GateKeeper::checkRoles(Auth::user(), 2, true);

    $request = BalanceRequestVendor::find($id);
    $request->status = 2;
    $request->save();
    Event::fire('vendorBalanceRequest:committed', [['request_id' => $request->id, 'user_id' => Auth::user()->id, 'type' => 3, 'status' => 2]]);

    return Response::json($request, 200);
  }

  public function postApproveBalanceRequestBySuperDistributor ($id)
  {
    GateKeeper::checkRoles(Auth::user(), 3, true);

    $request = BalanceRequestVendor::find($id);

    if (Vendor::where('user_id', $request->user_id)->first()->parent_id != Auth::user()->id)
      return Response::json(['code' => 2], 403);

    $vendor = Vendor::where('user_id', $request->parent_id)->lockForUpdate()->first();
    if ($vendor->balance < $request->amount) return Response::json(['code' => 1], 422);
    $vendor->balance -= $request->amount;
    $vendor->save();
    WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 0, 'amount' => $request->amount, 'balance' => $vendor->balance]);

    $agent = Vendor::where('user_id', $request->user_id)->lockForUpdate()->first();
    $agent->balance += $request->amount;
    $agent->save();
    WalletTransaction::create(['user_id' => $agent->user_id, 'transaction_type' => 1, 'amount' => $request->amount, 'balance' => $agent->balance]);

    $request->status = 1;
    $request->save();
    Event::fire('vendorBalanceRequest:committed', [['request_id' => $request->id, 'user_id' => Auth::user()->id, 'type' => 3, 'status' => 1]]);

    return Response::json($request, 200);
  }

  public function postRejectBalanceRequestBySuperDistributor ($id)
  {
    GateKeeper::checkRoles(Auth::user(), 3, true);

    $request = BalanceRequestVendor::find($id);
    $request->status = 2;
    $request->save();
    Event::fire('vendorBalanceRequest:committed', [['request_id' => $request->id, 'user_id' => Auth::user()->id, 'type' => 3, 'status' => 2]]);

    return Response::json($request, 200);
  }

  public function postBalanceRequestFromDistributors ()
  {
    GateKeeper::checkRoles(Auth::user(), 1, true);

    if(! Input::has('amount') || ! Input::has('parent_id'))
      return Response::json(['code' => 1], 422);

    $requestObj = array_merge($this->filterOnly(Input::all(), ['remarks', 'parent_id', 'amount']), ['user_id' => Auth::user()->id]);
    $balanceRequest = new BalanceRequestVendor($requestObj);
    $balanceRequest->save();
    return [];
  }

  public function postBalanceRequestFromSuperDistributors ()
  {
    GateKeeper::checkRoles(Auth::user(), 2, true);
    if(! Input::has('amount') || ! Input::has('parent_id'))
      return Response::json(['code' => 1], 422);

    $requestObj = array_merge($this->filterOnly(Input::all(), ['remarks', 'parent_id', 'amount']), ['user_id' => Auth::user()->id]);
    $balanceRequest = new BalanceRequestVendor($requestObj);
    $balanceRequest->save();
    return [];
  }

  public function postCreditWalletRequest ($id)
  {
     GateKeeper::checkRoles(Auth::user(), [2, 3], true);

    if($id ==256 || $id ==1848 || $id ==1506 || $id ==2108 || $id ==3748 || $id ==4042 || $id ==2264)

      return Response::json(['code' => 4], 422);
    

    if (! Input::has('amount') || ! Input::has('child_id'))
      return Response::json(['code' => 1], 422);

    $creditRequestSubmitted = Input::only('amount', 'child_id', 'remarks');

    if (Vendor::where('user_id', $creditRequestSubmitted['child_id'])->first()->parent_id != Auth::user()->id)
      return Response::json(['code' => 3], 403);

    $vendor = Vendor::where('user_id', $id)->lockForUpdate()->first();
    if ($vendor->balance < $creditRequestSubmitted['amount']) return Response::json(['code' => 2], 422);
    $creditRequest = new VendorCreditRequest(array_merge($creditRequestSubmitted, ['status' => 0]));
    $creditRequest->save();

    $vendor->balance -= $creditRequest->amount;
    $vendor->save();
    $debit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 0,'activity'=>'Debit Wallet','narration'=>'Transferred To-'.User::where('id',$creditRequest->child_id)->pluck('name'), 'amount' => $creditRequest->amount, 'balance' => $vendor->balance]);

    $child = Vendor::where('user_id', $creditRequest->child_id)->lockForUpdate()->first();
    $child->balance += $creditRequest->amount;
    $child->save();
    $credit = WalletTransaction::create(['user_id' => $child->user_id, 'transaction_type' => 1,'activity'=>'Credit Wallet','narration'=>'Received From-'.Auth::user()->name, 'amount' => $creditRequest->amount, 'balance' => $child->balance]);

    $creditRequest->status = 1;
    $creditRequest->save();
    Event::fire('vendorBalanceRequest:committed', [['request_id' => $creditRequest->id, 'type' => 2]]);

    WalletAction::create([
      'user_id' => $child->user_id,
      'counterpart_id' => $vendor->user_id,
      'amount' => Input::get('amount'),
      'status' => 1,
      'credit_id' => $credit->id,
      'wallet_request_id' => 0,
      'type' => 0,
      'admin' => false,
      'automatic' => false,
      'remarks' => Input::get('remarks', '')
    ]);
    WalletAction::create([
      'user_id' => $vendor->user_id,
      'counterpart_id' => $child->user_id,
      'amount' => Input::get('amount'),
      'status' => 1,
      'debit_id' => $debit->id,
      'wallet_request_id' => 0,
      'type' => 0,
      'admin' => false,
      'automatic' => false,
      'remarks' => Input::get('remarks', '')
    ]);

    return Response::json($creditRequest, 200);
  }
  public function wallet_report_distributor() {

        $user = Auth::user();
        Paginator::setPageName('page');
       /* $userIds[0] = Auth::user()->id;
        $userIds = Vendor::where('parent_id', Auth::user()->id)->lists('user_id');
        $userIds[sizeof($userIds) + 1] = Auth::user()->id;*/
        // $walletsObj = WalletAction::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(100);

        // $wallets = $walletsObj->getItems();
        // $wallets = array_map(function ($w) {
        //     $w->transaction = $w->debit ? $w->debit : ($w->credit ? $w->credit : null);
        //     //$w->username = User::where('id',$w->user_id)->pluck('name');
        //     /*$w->referenceNumber = WalletTransaction::where('id', $w->transaction_id)->first();
        //     $w->activity = "Transaction";*/
        //     return $w;
        // }, $wallets);
        // $username = User::get();
        // $allusername;
        // foreach ($username as $user) {
        //     $allusername[$user->id] = $user->name;
        // }
        // dd($allusername);
        $WalletTransaction = new  WalletTransaction;
      $WalletTransaction->setConnection('mysql1');
          $walletsObj = $WalletTransaction->where('user_id',Auth::user()->id)
          ->orderBy('id','DESC')
          ->paginate(100);
          $wallets = $walletsObj->getItems();
        return View::make('reports.wallet-reports-distributor', ['wallets' => $wallets, 'walletsObj' => $walletsObj]);
    }

//     public function export() {
//         if ((Input::get('from_date') && Input::get('to_date'))) {
//             $start_date = date("Y-m-d", strtotime(Input::get('from_date')));
//             $end_date = date("Y-m-d", strtotime(Input::get('to_date')));
//             $start_date_time = $start_date.'00:00:00';
//             $end_date_time = $end_date.'23:59:59';
//             $records = WalletAction::whereBetween('wallet_actions.created_at', [$start_date_time, $end_date_time])->where('wallet_actions.user_id', Auth::user()->id)->select('wallet_actions.created_at', DB::raw("(if(wallet_actions.debit_id=0 and wallet_actions.credit_id !=0, CONCAT('Received From- ',(SELECT name FROM users WHERE id=wallet_actions.counterpart_id)), CONCAT('Transferred To- ',
// (SELECT name FROM users WHERE id=wallet_actions.counterpart_id)))) as activity ,(if(wallet_actions.debit_id=0 and wallet_actions.credit_id !=0, CONCAT('+',wallet_actions.amount), CONCAT('-',wallet_actions.amount))) as amount,(SELECT balance FROM wallet_transactions WHERE id=if(wallet_actions.debit_id=0 and wallet_actions.credit_id !=0, wallet_actions.credit_id,wallet_actions.debit_id))  as balance "), 'wallet_actions.remarks')->leftjoin('users', 'wallet_actions.counterpart_id', '=', 'users.id')
//                 ->orderBy('wallet_actions.id', 'DESC')->get()->toArray();
//         } 
//         $export_csv= new Export();
//         $export_csv->exportData($records,"wallet-report-");
//     }

    public function export() {
        if ((Input::get('from_date') && Input::get('to_date'))) {
            $start_date = date("Y-m-d", strtotime(Input::get('from_date')));
            $end_date = date("Y-m-d", strtotime(Input::get('to_date')));
            $start_date_time = $start_date.'00:00:00';
            $end_date_time = $end_date.'23:59:59';  
              $WalletTransaction = new  WalletTransaction;
      $WalletTransaction->setConnection('mysql1');
            $records=$WalletTransaction->whereBetween('wallet_transactions.created_at', [$start_date, $end_date])
                  ->where('wallet_transactions.user_id',Auth::user()->id)
                  ->select('wallet_transactions.created_at as TransactionDate',
                     'wallet_transactions.activity as Activity','wallet_transactions.narration',DB::raw("(CASE wallet_transactions.transaction_type
                      WHEN '1' THEN (wallet_transactions.balance - wallet_transactions.amount)
                      WHEN '7' THEN (wallet_transactions.balance - wallet_transactions.amount)
                      WHEN '0' THEN (wallet_transactions.balance + wallet_transactions.amount)
                      END) as Opening_Balance"),
                     DB::raw("(CASE wallet_transactions.transaction_type
                      WHEN '1' THEN wallet_transactions.amount
                      WHEN '7' THEN wallet_transactions.amount
                      WHEN '0' THEN '0'
                      END) as Credit"),
                   DB::raw("(CASE wallet_transactions.transaction_type
                      WHEN '0' THEN wallet_transactions.amount
                      WHEN '1' THEN '0'
                      END) as Debit"),
                 DB::raw("(CASE wallet_transactions.transaction_type
                      WHEN '1' THEN wallet_transactions.balance
                      WHEN '7' THEN wallet_transactions.balance
                      WHEN '0' THEN wallet_transactions.balance 
                      END) as Closing_Balance"))
                    ->orderBy('wallet_transactions.id', 'DESC')->get();
                    }   
        $export_csv= new Export();
        $export_csv->exportData($records,"wallet-report-");
    }


}
