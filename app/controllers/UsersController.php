<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;
use Acme\SMS\SMS;
/**
*
*/
class UsersController extends BaseController
{

	function __construct()
	{
		$this->beforeFilter('auth.json', ['only' => [
			'postUpdateStatus', 'postAddAdmin', 'postUpdateUserObj', 'postKYCDetails'
		]]);
		$this->beforeFilter('auth', ['only' => [
			'getAdmins', 'getAddAdmin', 'getUsers'
		]]);
	}

	public function getAdmins ()
	{
		GateKeeper::check(Auth::user(), ['superadmin-list-admins']);
		return View::make('admins.list');
	}

	public function getProfile($id)
	{
		$data['userProfile'] = User::where('id', $id)->with('vendor')->with('vendorBankAccount')->first();
		$data['userProfile']['vendor']->type = $this->getVendorTypeById($data['userProfile']['vendor']->type);
		$parent = User::where('id', $data['userProfile']['vendor']->parent_id)->first();
		$data['userProfile']['vendor']->parent = ['id' => $parent['id'], 'name' => $parent['name']];
		$data['cities'] = City::get();
		$data['banks'] = Bank::get();
		return View::make('users.profile')->with($data);
	}

	public function postProfile ($id)
	{
		if (! Auth::user()) return Response::json('Unauthorized', 401);
		if (Auth::user()->id != $id) return Response::json('Unauthorized', 401);

		$profile_pic =Input::file('file1');
        if ( $profile_pic == NULL) {
            return Response::json(['message' => 'Please upload Profile picture!'], 406);
        }
		$destinationPath =  public_path(). '/upload/profile';
        $file1=Input::file('file1');
        $user_profile_url = $id . '_user_profile.' . $file1->getClientOriginalExtension();
        $file1->move($destinationPath . '/', $user_profile_url);

		$gst_no = Input::get('gst_no');
		$vendorSubmitted = ['user_profile_url'=>$user_profile_url, 'gst_no'=>$gst_no];
		$vendorBankSubmitted = Input::only('bank_id', 'acc_type', 'acc_no', 'ifsc_code');
		$vendor = Vendor::where('user_id', $id)->update($vendorSubmitted);
		$vendorBank = VendorBankAccount::create(array_merge($vendorBankSubmitted, ['user_id' => $id]));
		return Response::json('success', 200);
	}

	/*
		Funtion to get Certificate from user ID
	*/
	public function getCertificate($id)
	{
		$data['certificate'] = User::join('user_vendors as v', 'users.id', '=', 'v.user_id')
			->join('cities as c', 'c.id', '=', 'v.city')
            ->select('users.name as username','c.name as city')            
            ->where('users.id', $id)
            ->first();

		return View::make('users.certificate')->with($data);
	}

	public function getAddAdmin ()
	{
		GateKeeper::check(Auth::user(), ['s-u-a']);
		return View::make('admins.add');
	}

	public function postAddAdmin ()
	{
		GateKeeper::check(Auth::user(), ['a-u-c'], true);
		if (! Input::has('email')) {
			return Response::json(['message' => 'No email ID sent'], 422);
		}
		$duplicateUser = User::whereEmail(Input::get('email'))->first();
		if ($duplicateUser) {
			return Response::json(['message' => 'Email ID is already used'], 409);
		}
		// @todo validate input data
		$user = $this->setData(Input::except('permissions'), new User);
		$user->status = 1;
		$user->password = Hash::make('password');
		$user->save();
		if (count(Input::get('permissions', [])) > 0) {
			Permission::insert(array_map(function ($x) use($user) {
				return ['user_id' => $user->id, 'permission' => $x];
			}, Input::get('permissions')));
		}
		return $user;
	}

	public function postUpdateStatus ($userId)
	{
		GateKeeper::check(Auth::user(), ['s-u-c'], true);

		if (! Input::has('status')) {
			return Response::json(['message' => 'Incomplete request'], 400);
		}
		$user = User::find($userId);
		if (! $user) return Response::json(['message' => 'Resource not found'], 500);
		$user->status = Input::get('status');
		$user->save();
		return $user;
	}

	public function postUpdateUserObj ($userId)
	{
		$user = User::find($userId);
		if (! $user) return Response::json(['message' => 'User not found'], 500);
		// @todo: filter input, restrict access to back-office admins
		$user = $this->setData(Input::all(), $user);
		$user->save();
		return $user;
	}

	public function postChangePassword ($userId)
	{
		$user = Auth::user();
		if (! $user || $user->id != $userId)
			return Response::json([], 403);
		if (! Input::has('password') || ! Input::has('password_confirmation') || ! Input::has('old_password'))
			return Response::json(['code' => 1], 422);
		if (! Hash::check(Input::get('old_password'), $user->password))
			return Response::json(['code' => 2], 422);
		if (Input::get('password') != Input::get('password_confirmation'))
			return Response::json(['code' => 3], 422);

		$user->password = Hash::make(Input::get('password'));
		$user->save();
		return [];
	}

	public function postChangePasswordDMT ($userId)
	{
		if (! Request::header('auth')) return Response::json(['message' => 'Missing auth token'], 422);
		$user = Auth::user();
		if (! $user || $user->id != $userId)
			return Response::json([], 403);

		if (! Input::has('password') || ! Input::has('password_confirmation') || ! Input::has('old_password'))
			return Response::json(['code' => 1], 422);
		if (! Hash::check(Input::get('old_password'), $user->password))
			return Response::json(['code' => 2], 422);
		if (Input::get('password') != Input::get('password_confirmation'))
			return Response::json(['code' => 3], 422);

		$user->password = Hash::make(Input::get('password'));
		$user->save();

		return Response::json($user, 200);

	}

	public function getUserDetails ($userId)
	{
		$user = User::find($userId);
		if (! $user) return Redirect::to('/')->with('message', 'User not found');
		return View::make('users.kyc')->with('profile_user', $user);
	}

	public function postKYCDetails ($userId)
	{
		$kyc = KYC::where('user_id', $userId)->first();
		if (! $kyc) $kyc = new KYC();
		$kyc = $this->setData(Input::all(), $kyc);
		$kyc->user_id = $userId;
		$kyc->save();
		Event::fire('kycdetails.updated', [$kyc]);
		return [];
	}

	public function getUsers ()
	{
		return View::make('users.list');
	}

	public function getCommissionsIndex ()
	{
		$vendors = User::getVendors();
		return View::make('commissions.index')
			->withVendors($vendors);
	}


	private function getVendorTypeById ($type)
	{
		$dict = [
        1 => [
            'id' => 1,
            'type' => 'agent',
            'parent_type_id' => 2,
            'parent' => 'distributor'
        ],
        2 => [
            'id' => 2,
            'type' => 'distributor',
            'parent_type_id' => 3,
            'parent' => 'super distributor'
        ],
        3 => [
            'id' => 3,
            'type' => 'super distributor',
        ],
        4 => [
            'id' => 4,
            'type' => 'sales executive',
        ],
        5 => [
            'id' => 5,
            'type' => 'area sales officeer',
        ],
        6 => [
            'id' => 6,
            'type' => 'area sales manager',
        ],
        7 => [
            'id' => 7,
            'type' => 'cluster head',
        ],
        10 => [
            'id' => 10,
            'type' => 'state head',
        ],
        11 => [
            'id' => 1,
            'type' => 'regional head',
        ],
      ];
      return $dict[$type];
	}

	public function getChangePasswordPage ($userId)
	{
		if ( ! Auth::user() || Auth::user()->id != $userId)
			return Redirect::to('/');
		return View::make('users.change-password');
	}

	public function postDebitWallet ($userId)
	{
		if($userId ==256 || $userId ==1848 || $userId ==1506 || $userId ==2108 || $userId ==3748 || $userId ==4042 || $userId ==2264)

			return Response::json(['code' => 5], 422);
		if (! Input::has('amount')) 
			return Response::json(['code' => 1], 422);
		if (! is_int(Input::get('amount')) && ! is_double(Input::get('amount'))) 
			return Response::json(['code' => 1], 422);
		
		$user = User::find($userId);
		if (! $user) return Response::json(['code' => 404], 422);
		Log::info($user->vendorDetails->parent_id != Auth::user()->id);
		Log::info($user->vendorDetails->parent_id .' -- '. Auth::user()->id);
		if ($user->vendorDetails->parent_id != Auth::user()->id) 
			return Response::json([], 403);

		// $debitRequest = DebitRequest::create(
		// 	['user_id' => Auth::user()->id, 'child_id' => $userId, 'amount' => Input::get('amount'), 'remarks' => Input::get('remarks', '')]);

		if ($user->vendorDetails->balance < Input::get('amount')) {
			// $debitRequest->status = 3;
			// $debitRequest->save();
			return Response::json(['code' => 4], 422);
		}

		$userVendorDetails = Vendor::lockForUpdate()->find($user->vendorDetails->id);
		if (! $userVendorDetails) return Response::json(['code' => 2], 422);
		$parentVendorDetails = Vendor::lockForUpdate()->where('user_id', Auth::user()->id)->first();
		if (! $parentVendorDetails) return Response::json(['code' => 3], 422);

		$userVendorDetails->balance -= Input::get('amount');
		$userVendorDetails->save();
		$debitTx = WalletTransaction::create(['user_id' => $userId, 'transaction_type' => 0, 'amount' => Input::get('amount'), 'balance' => $userVendorDetails->balance, 'activity' => 'Debit-Wallet', 'narration'=>'Transferred to-'.Auth::user()->name]);

		$parentVendorDetails->balance += Input::get('amount');
		$parentVendorDetails->save();
		$creditTx = WalletTransaction::create(['user_id' => Auth::user()->id, 'transaction_type' => 1, 'amount' => Input::get('amount'), 'balance' => $parentVendorDetails->balance,'activity'=>'Credit-Wallet','narration'=>'Received from -'.User::where('id',$userId)->pluck('name')]);

		// $debitRequest->debit_id = $debitTx->id;
		// $debitRequest->credit_id = $creditTx->id;
		// $debitRequest->status = 1;
		// $debitRequest->save();

		WalletAction::create([
      'user_id' => $userVendorDetails->user_id,
      'counterpart_id' => $parentVendorDetails->user_id,
      'amount' => Input::get('amount'),
      'status' => 1,
      'debit_id' => $debitTx->id,
      'wallet_request_id' => 0,
      'type' => 0,
      'admin' => false,
      'remarks' => Input::get('remarks', ''),
      'automatic' => false
    ]);
    WalletAction::create([
      'user_id' => $parentVendorDetails->user_id,
      'counterpart_id' => $userVendorDetails->user_id,
      'amount' => Input::get('amount'),
      'status' => 1,
      'credit_id' => $creditTx->id,
      'wallet_request_id' => 0,
      'type' => 0,
      'admin' => false,
      'remarks' => Input::get('remarks', ''),
      'automatic' => false
    ]);

		return [];
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
//dd($mobileChecker);
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

    public function getResetSession($id)
    {
    	return View::make('users.reset-session');
    }

    public function postResetSession($id)
    {
    	if (! Auth::user()) return Response::json('Unauthorized', 401);

		if (Auth::user()->id != $id) return Response::json('Unauthorized', 401);
		$enabled = Input::get('enabled');
		if($enabled=='enabled')
		{
			$resetSession = User::where('id', $id)->update(['reset_session'=>1]);
			return Response::json('successfully enabled', 200);	
		}
		$disabled = Input::get('disabled');
		if($disabled=='disabled')
		{
			$resetSession = User::where('id', $id)->update(['reset_session' =>0]);
			return Response::json('successfully disabled', 200);	
		}		
    }

    public function smsUserBalance(){
		$userObj = DB::table('user_vendors')
            ->select(
            	'user_id',
                'balance',
                'csr_id'
            )
            ->where('balance', '>=', 500000)
            ->whereNotIn('user_id', array(13,311,1,2,1024));
       	$users = $userObj->get();
       	if(count($users) > 0){
       		$id    = array();
	       	foreach ($users as $user) {
	       		$balance = $user->balance;
	       		$id[] = $user->user_id . ' - ' . $balance;
	       		$csr_id = $user->csr_id;
	       		$vendor_submitted = ['csr_id'=>$csr_id."t"];
	       		$users_update = Vendor::where('user_id', $user->user_id)->update($vendor_submitted);
	       	}
	       	$ids = implode("'\r\n'", $id);
	       	$msg = "'".$ids."'";
	       	$phone_no = '9029323171';
	       	$date = date('d-m-Y');

	       	$message = urlencode("Date: ".$date." - AEPS\r\nDear admin below users having balance more than 5 Lakhs.\r\nUser ID - Amount\r\n".$msg."."); 

	       	SMS::send($phone_no, $message);
	       	echo 'Message Sent';		
       	}else{
       		echo 'No Data';
       	}
    }

    public function smsUserClosingBalance(){
		$agentObj = DB::table('user_vendors')
            ->select(DB::raw('SUM(balance) as balance'))
            ->where('type', 1)
            ->whereNotIn('user_id', array(311));
       	$agents = $agentObj->get();
		$agent_closing_balance = number_format($agents[0]->balance, 2);

		$distObj = DB::table('user_vendors')
            ->select(DB::raw('SUM(balance) as balance'))
            ->where('type', 2)
            ->whereNotIn('user_id', array(13));
       	$dists = $distObj->get();
		$dist_closing_balance = number_format($dists[0]->balance, 2);

		$superDistObj = DB::table('user_vendors')
            ->select(DB::raw('SUM(balance) as balance'))
            ->where('type', 3)
            ->whereNotIn('user_id', array(13));
       	$super_dists = $superDistObj->get();
		$super_dist_closing_balance = number_format($super_dists[0]->balance, 2);

       	$phone_no = '9004835502';
       	$date = date('d-m-Y');

       	$message = urlencode("Date: ".$date." - AEPS\r\nAgent Closing Balance\r\n".$agent_closing_balance."\r\n\r\nDistributor Closing Balance\r\n".$dist_closing_balance."\r\n\r\nSuper Distributor Closing Balance\r\n".$super_dist_closing_balance.""); 

       	SMS::send($phone_no, $message);
       	echo 'Message Sent';
    }

    public function smsCommissionRate(){
		$commissionObj = DB::table('commission_rates')
            ->select('user_id', 'rate')
            ->where('master_id', 4)
            ->where('rate_type', 2);
       	$commissions = $commissionObj->get();
       	if(count($commissions) > 0){
       		$id    = array();
	       	foreach ($commissions as $commission) {
	       		$rate = $commission->rate;
	       		$id[] = $commission->user_id . ' - ' . $rate;
	       	}
	       	$ids = implode("'\r\n'", $id);
	       	$msg = "'".$ids."'";

	       	$phone_no = '9004835502';
	       	$date = date('d-m-Y');
	       	$message = urlencode("Date: ".$date." - AEPS\r\nDear admin below users having commission rates. \r\nUser ID - Amount\r\n".$msg.".");
	       	SMS::send($phone_no, $message);
	       	echo 'Message Sent';
       	}else{
       		echo 'No Data';
       	}
    }

    public function smsCreditCommissions(){
		$commissionObj = DB::table('wallet_transactions')
            ->select('user_id', 'amount')
            ->where('activity', 'like', '%' . 'credit-commission' . '%')
            ->where('amount', '>=', 8)
            ->where('created_at', '>=', date('Y-m-d').' 00:00:00');
       	$commissions = $commissionObj->get();

       	if(count($commissions) > 0){
       		$id    = array();
	       	foreach ($commissions as $commission) {
	       		$amount = $commission->amount;
	       		$id[] = $commission->user_id . ' - ' . $amount;
	       	}
	       	$ids = implode("'\r\n'", $id);
	       	$msg = "'".$ids."'";
	       	$phone_no = '9004835502';
	       	$date = date('d-m-Y');
	       	$message = urlencode("Date: ".$date." - AEPS\r\nDear admin below users having more than Rs. 8.00 commission. \r\nUser ID - Amount\r\n".$msg.".");
	       	SMS::send($phone_no, $message);
	       	echo 'Message Sent';
       	}else{
       		echo 'No Data';
       	}
    }
}
