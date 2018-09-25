<?php
use Acme\Auth\Auth;
use Acme\SMS\SMS;

/**
*  A controller that deals with action APIs
*/
class ActionsController extends BaseController
{
	function __construct()
	{
		
	}

	public function postPasswordResetOTP ()
	{
		if (! Input::has('phone_no')) {
			return Response::json(['message' => 'Missing info'], 422);
		}
		
		$headers = [
		   	'Accept' => 'application/json',
		    'Content-Type' => 'application/json'
		];

	    $data = [
	      'mobileNo'=>(string)Input::get('phone_no')
	    ];


		         
		     
		$body = Unirest\Request\Body::json($data);

		$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/ForgotPasswordcheckmobileno', $headers, $body);

		if($response->body->status == 1)
	    {
	    	$otp = mt_rand(1000, 9999);
			$otpObj = new PasswordResetOTP(['user_id' =>  Input::get('phone_no'), 'otp' => $otp.'', 'ip' => Request::getClientIp()]);
			$otpObj->save();
			// @todo send SMS
			// SMS the OTP
			SMS::send( Input::get('phone_no'), 'Yor OTP is '.$otp.'. Digital India Payments.');
			return [];
	    }else{
	    	return Response::json($response->body->description, 400);
	    }
		
	}

	public function postNewPasswordOTP ()
	{
		if (! Input::has('phone_no') || ! Input::has('otp') || ! Input::has('password') || ! Input::has('password_confirmation')) {
			return Response::json(['message' => 'Missing info'], 422);
		}
		if (Input::get('password') !=Input::get('password_confirmation')) {
			return Response::json(['message' => 'Password and confirm password is not same'], 422);
		}
		$user = User::where('phone_no', '9999900000')->first();
		//if (! $user) return Response::json(['code' => 404], 422);
		$status = PasswordResetOTP::getOTP(Input::get('otp'), Input::get('phone_no'));
		if (! $status) return Response::json(['code' => 1], 422);

		//$p = Hash::make(Input::get('password'));
		// $user->password = $p;
		// return Response::json($user->password, 500);
		// $user->save();

		$headers = [
		   	'Accept' => 'application/json',
		    'Content-Type' => 'application/json'
		];

	    $data = [
	      'password' =>(string)Input::get('password'),
	      'mobileNo'=>(string)Input::get('phone_no')
	    ];


		         
		     
		$body = Unirest\Request\Body::json($data);

		$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/ForgotPasswordOTPSend', $headers, $body);
//dd($response);
		
		// if(isset($response->body->status == 1))
	 //    {
		
	   	if($response->body->status == 1)
	    {
	     	  
			$data = [
      'mobilenumber' => Input::get('phone_no')
       ];
      
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/logout', $headers, $body);

		$data = [
	      'phone_no' => (string)Input::get('phone_no'),
	      'password' =>(string)Input::get('password'),
	      'captcha' => '123'
	    ];
      
    	$body = Unirest\Request\Body::json($data);

  		$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/login', $headers, $body);
  		if($response->body->status == 1)
	    {
  		$response_data=json_decode($response->raw_body);
		   //dd($response_data);
		  
		  if($response_data->user_type == 1)
		  {
		    Cookie::queue('user',  $response_data->sessiontoken, 60);
	  		Cookie::queue('userid',  $response_data->userid, 60);
	   		Cookie::queue('bcagent',  $response_data->bcagent, 60);
	   		Cookie::queue('user_type',  $response_data->user_type, 60);
	   		Cookie::queue('parentid',  $response_data->parentid, 60);
	   		Cookie::queue('mobileno',  $response_data->mobileno, 60);
	    	Cookie::queue('user_name',  $response_data->user_name, 60);
	  		Cookie::queue('session_timeout',  $response_data->session_timeout, 60);
    
		   Auth::login($user);
		   return $user;

		   return Response::json(['code' => 1], 422);

		  }else
		  {
		    Cookie::queue('user_type',  $response_data->user_type, 60);
   			Cookie::queue('parentid',  $response_data->parentid, 60);
    		Cookie::queue('userid',  $response_data->userid, 60);
    		Cookie::queue('mobileno',  $response_data->mobileno, 60);
        	Cookie::queue('user_name',  $response_data->user_name, 60);

		    Auth::login($user);
		   return $user;
		  }
			
		}else{
			return Response::json('1', 400);
		}
			//$vendor->freshness_factor = $rblResponse->nextFreshnessFactor;
	       	//$vendor->save();

			

	    }else
	    {
	    	
	     
	    }  
	    // }else
	    // {
	    // 	return Response::json(['code' => 1], 422);
	     
	    // }  

		
	    // if($vendor->type == 1) {
	    //   $rblResponse = $this->loginRBL($vendor);
	    //   if (! $rblResponse || $rblResponse->responseCode != '00')
	    //   {
	    //     \Log::info(json_encode($rblResponse));
	    //     return Response::json(
	    //     ['message' => 'Could not authenticate with the bank.', 'code' => 2], 403);
	    //   }
	    //   \Log::info(json_encode($rblResponse));
	    //   /** Update the vendor's freshness_factor with the new freshness_factor
	    //   *   received from the bank on successful authentication
	    //   */
	       
	    // }


		
	}

	public function postSessionResetOTP ()
	{
		if (! Input::has('phone_no')) {
			return Response::json(['message' => 'Missing info'], 422);
		}
		$user = User::where('phone_no', Input::get('phone_no'))
			->where('type', 4)
			->first();
		if (! $user) return Response::json(['code' => 1], 422);
		$otp = mt_rand(1000, 9999);
		$otpObj = new SessionResetOTP(['user_id' => $user->id, 'otp' => $otp.'', 'ip' => Request::getClientIp()]);
		$otpObj->save();
		// @todo send SMS
		// SMS the OTP
		SMS::send($user->phone_no, 'Yor OTP is '.$otp.'. Digital India Payments.');
		return [];
	}

	private function loginRBL ($vendor)
  {
    date_default_timezone_set('Asia/Kolkata');
		$string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$string_shuffled = str_shuffle($string);
		$requestId = substr($string_shuffled, 1, 6);

  	//Call API for getting the freshness factor
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];
  	$data = [
      'terminalId' => $vendor->terminal_id,
      'freshnessFactor' => '00',
      'transType' => '106',
      'csrId' => $vendor->csr_id,
      'requestId' => $requestId,
      'resentCount' => '1',
      'deviceId' => $vendor->device_id,
      'txnTime' => date("M j, Y G:i:s A"),
      'object' => [
        'csrPassword' => $vendor->csr_password,
        'csrId' => $vendor->csr_id
      ],

      'version'=>'1.2.8.1'
    ];
    $body = Unirest\Request\Body::json($data);
    // @TODO: Use events to log.
//    $bank_login_log = BankLoginLog::create(['user_id' => $vendor->user_id, 'request' => $body]);
    Log::info("Login request: ".$body);

    $response = Unirest\Request::post(getenv('RBL_URL'), $headers, $body);
    $bank_login_log->response = json_encode($response->body);
    $bank_login_log->save();

    if ($response->code >= 400) {
      Log::info($response->code.' '.json_encode($response->body));
      return false;
    }

    return $response->body;
  }

	


}