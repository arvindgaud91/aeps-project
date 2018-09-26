<?php

use Acme\Auth\Auth;

class SessionsController extends BaseController {

  function __construct()
  {
    $this->beforeFilter('guest',
      ['only' => ['getLogin', 'getRegister', 'getPasswordResetPage', 'getVerifyEmail']]);

    $this->beforeFilter('guest.json',
      ['only' => ['postLogin', 'postRegister', 'postSetPasswordResetToken', 'postResetPassword', 'postVerifyPhone', 'postResendEmailToken', 'postResendSMSToken']]);
  }

  /**
  * GET /login
  */
   public function getLogin()
  {
    return View::make('sessions.login');
    
  }
public function getauth()
  {
    //dd(Input::get('auth'));
   // \Cookie::queue('tracker', Input::get('auth'), 60*24*7);

    return Redirect::to('/logout');

  }

  /**
  * POST /login
  * Err codes:
  * 3 - mobile not verified, 2 - email not verified, 4 - status is inactive, 5 - bank authentication failed
  */
  public function postLogin()
  {
   // dd(Input::all());
      $bankLoginResponse = $this->bankLogin(Input::all());
     //dd($bankLoginResponse);
if($bankLoginResponse->status == 1)
{ 
	
  $datas=array('phone_no'=>Input::get('phone_no'),'password'=>Input::get('password'),'captcha'=>Input::get('captcha'));

    $user = Auth::validate($datas);   
    //$user = Auth::validate(Input::all());
    if (! $user)
    {
      return Response::json(['message' => 'Invalid credentials'], 500);
    }
    // if ($user->email_verified == 0) {
    //   return Response::json(['message' => 'Email is not yet verified.', 'code' => 2], 403);
    // }
    if ($user->phone_verified == 0) {
      return Response::json(
          ['message' => 'Mobile no is not yet verified.', 'code' => 3, 'email' => $user->email], 403);
    }
    if ($user->status == 0)
    {
      return Response::json(
          ['message' => 'Account is deactive.', 'code' => 4, 'email' => $user->email], 403);
    }
    if ($user->web_auth_code != NULL) {
      return Response::json(['message' => 'User is currently logged in from another device.', 'code' => 6], 409);
    }

    $vendor = Vendor::where('user_id', $user->id)->first();
  

    //$user->web_auth_code = getRandomString(6);
    //$user->save();

    Auth::login($user);

    $settlement_flag = Settlement::where('user_id', $user->id)->first();
     if(!$settlement_flag){
      Session::put('settlement',1);
     }else{
      Session::forget('settlement');
     }
     

    // $bankLoginResponse = $this->bankLogin(Input::all());
 
   Event::fire('user.login', ['user' => Auth::user(), 'vendor' => $vendor]);
    return ['status' => 1];
  

}else
{ 

  //dd($bankLoginResponse);
  if(isset($bankLoginResponse->loginstatus))
  if($bankLoginResponse->loginstatus == 1) return ['desc'=>$bankLoginResponse->description,'status'=>'0','loginstatus'=>'1'];




    return  ['status' => 0];

}
     

    
  }

  public function postLogindmtv3($phone_no,$password)
  {

    //$this->getdmtLogout();


$datass=array("phone_no"=>$phone_no, "password"=>urldecode($password), "captcha"=>"123");

      //$bankLoginResponse = $this->bankLogin($datass);
     //dd($bankLoginResponse);
// if($bankLoginResponse->status == 1)
// { 

  $datas=array('phone_no'=>'9999900000','password'=>'123456','captcha'=>Input::get('captcha'));

          $user = Auth::validate($datas);
    //$user = Auth::validate(Input::all());
    if (! $user)
    {
      return Response::json(['message' => 'Invalid credentials'], 500);
    }
    // if ($user->email_verified == 0) {
    //   return Response::json(['message' => 'Email is not yet verified.', 'code' => 2], 403);
    // }
    if ($user->phone_verified == 0) {
      return Response::json(
          ['message' => 'Mobile no is not yet verified.', 'code' => 3, 'email' => $user->email], 403);
    }
    if ($user->status == 0)
    {
      return Response::json(
          ['message' => 'Account is deactive.', 'code' => 4, 'email' => $user->email], 403);
    }
    if ($user->web_auth_code != NULL) {
      return Response::json(['message' => 'User is currently logged in from another device.', 'code' => 6], 409);
    }

    $vendor = Vendor::where('user_id', $user->id)->first();
  

    // $user->web_auth_code = getRandomString(6);
    // $user->save();

    Auth::login($user);

    $settlement_flag = Settlement::where('user_id', $user->id)->first();
     if(!$settlement_flag){
      Session::put('settlement',1);
     }else{
      Session::forget('settlement');
     }
     

    // $bankLoginResponse = $this->bankLogin(Input::all());
 
   Event::fire('user.login', ['user' => Auth::user(), 'vendor' => $vendor]);
     return Redirect::to('http://paydmt.digitalindiapayments.com:8021');
  

// }else
// { 

//   //dd($bankLoginResponse);
//   if(isset($bankLoginResponse->loginstatus))
//   if($bankLoginResponse->loginstatus == 1) return ['desc'=>$bankLoginResponse->description,'status'=>'0','loginstatus'=>'1'];




//     return  ['status' => 0];

// }
     

    
  }
  /**
  *
  * Will authenticate the agent with the bank to obtain a freshness factor
  */
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
     'channel' =>'RDI1849363',
      'txnTime' => date("M j, Y G:i:s A"),
      'object' => [
        'csrPassword' => $vendor->csr_password,
        'csrId' => $vendor->csr_id
      ],

      'version'=>'1.2.8.1'
    ];
    $body = Unirest\Request\Body::json($data);
    // @TODO: Use events to log.
    //$bank_login_log = BankLoginLog::create(['user_id' => $vendor->user_id, 'request' => $body]);
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

  /**
  * POST /login-global
  * will auhtenticate users from this software for global single signin
  */
  public function postLoginGlobal ()
  {
    $user = Auth::validate(Input::all());
    if (! $user) {
      return Response::json(['message' => 'Invalid credentials'], 500);
    }
    if ($user->status == 0) {
      return Response::json(
          ['message' => 'Account is inactive.', 'code' => 4, 'email' => $user->email], 403);
    }
    Event::fire('user.login-global', [Auth::user()]);
    return $user;
  }

  public function postNewSessionOTP ()
  {
    if (! Input::has('phone_no') || ! Input::has('otp') || ! Input::has('password')) {
      return Response::json(['message' => 'Missing info'], 422);
    }
    $user = User::where('phone_no', Input::get('phone_no'))->first();
    if (! $user) return Response::json(['code' => 404], 422);
    $status = SessionResetOTP::getOTP(Input::get('otp'), $user->id);
    if (! $status) return Response::json(['code' => 1], 422);
    
    $user = Auth::validate(['phone_no' => Input::get('phone_no'), 'password' => Input::get('password')]);
    if (! $user)
    {
      return Response::json(['message' => 'Invalid credentials'], 500);
    }
    // if ($user->email_verified == 0) {
    //   return Response::json(['message' => 'Email is not yet verified.', 'code' => 2], 403);
    // }
    if ($user->phone_verified == 0) {
      return Response::json(
          ['message' => 'Mobile no is not yet verified.', 'code' => 3, 'email' => $user->email], 403);
    }
    if ($user->status == 0)
    {
      return Response::json(
          ['message' => 'Account is deactive.', 'code' => 4, 'email' => $user->email], 403);
    }

    $vendor = Vendor::where('user_id', $user->id)->first();
    if($vendor->type == 1) {
      $rblResponse = $this->loginRBL($vendor);
      if (! $rblResponse || $rblResponse->responseCode == '01')
      {
        \Log::info(json_decode($rblResponse));
        return Response::json(
        ['message' => 'Could not authenticate with the bank.', 'code' => 5], 403);
      }
      \Log::info(json_encode($rblResponse));
      /** Update the vendor's freshness_factor with the new freshness_factor
      *   received from the bank on successful authentication
      */
        $vendor->freshness_factor = $rblResponse->nextFreshnessFactor;
     $vendor->save();
    }

    $user->web_auth_code = getRandomString(6);
    $user->save();



    Auth::login($user);
    Event::fire('user.login', ['user' => Auth::user(), 'vendor' => $vendor]);
    return ['message' => 'Logged in successfully.'];
  }

  /**
  * GET /register
  */
  public function getRegister ()
  {
    return View::make('sessions.register');
  }

  /**
  * POST /register
  */
  public function postRegister ()
  {
    $rules = [
      'name' => 'required',
      'email' => 'required|email|unique:users',
      'phone_no' => 'required',
      'password' => 'required|min:5',
      'password_conf' => 'required|same:password'
    ];
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) {
      return Response::json(['message' => 'Validation failed.', 'errors' => $validator->messages()], 500);
    }
    $user = new User(Input::only('name', 'email', 'phone_no', 'password'));
    $user->password = Hash::make(Input::get('password'));
    $user->status = 1;
    $user->save();
    Event::fire('user.registered', [$user]);
    return $user;
  }

  /**
  * GET /logout
  */
  public function getLogout ()
  {
   
   $mobile=Input::get('mobilenumber'); 
    if(isset($mobile)){
       $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

     $data = [
      'mobilenumber' => Input::get('mobilenumber')
       ];
      
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService_V3/logout', $headers, $body);

  $response_data=json_decode($response->raw_body);
// $errorrem= new BankLoginLog();
//       $errorrem->request=json_encode($data);
//       $errorrem->response=json_encode($response);
//       $errorrem->save();
    }
   
       
      \Cookie::forget('user');
      \Cookie::forget('userid');
      \Cookie::forget('bcagent');
      \Cookie::forget('user_type');
      \Cookie::forget('parentid');
      \Cookie::forget('mobileno');
      \Cookie::forget('user_name');
      \Cookie::forget('tracker');
      \Cookie::forget('session_timeout');
      Session::forget('settlement');

    $user = Auth::user();
    if (! $user)
      return Redirect::to('/login');
    $user->web_auth_code = NULL;
    $user->save();
    Auth::logout();
    Event::fire('user.logout', [$user]);
    return Redirect::to('/login');
  }




 public function getdmtLogout ()
  {
   
   $mobile=Input::get('mobilenumber'); 
    if(isset($mobile)){
       $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

     $data = [
      'mobilenumber' => Input::get('mobilenumber')
       ];
      
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/logout', $headers, $body);

  $response_data=json_decode($response->raw_body);
// $errorrem= new BankLoginLog();
//       $errorrem->request=json_encode($data);
//       $errorrem->response=json_encode($response);
//       $errorrem->save();
    }
   
       
      \Cookie::forget('user');
      \Cookie::forget('userid');
      \Cookie::forget('bcagent');
      \Cookie::forget('user_type');
      \Cookie::forget('parentid');
      \Cookie::forget('mobileno');
      \Cookie::forget('user_name');
      \Cookie::forget('tracker');
      \Cookie::forget('session_timeout');
      Session::forget('settlement');

    $user = Auth::user();
    if (! $user)
     // return Redirect::to('/login');
    $user->web_auth_code = NULL;
    $user->save();
    Auth::logout();
    Event::fire('user.logout', [$user]);
   // return Redirect::to('/login');
  }


  /**
  * POST /password/actions/reset-token/
  */
  public function postSetPasswordResetToken ()
  {
    if (! Input::has('email')) {
      return Response::json(['message' => 'Invalid request.'], 400);
    }
    $user = User::where('email', Input::get('email'))->first();
    if (! $user) {
      return Response::json(['message' => 'User does not exist.'], 500);
    }
    $token = md5(time().'_'.$user->id);
    $pwdToken = PasswordResetToken::create([
      'user_id' => $user->id,
      'token' => $token,
      'ip' => Request::getClientIp()
    ]);
    Event::fire('user.password.reset-request', [$pwdToken]);
    return $pwdToken;
  }

  /**
  * GET /password/actions/reset-token/{token}
  */
  public function getPasswordResetPage ($token)
  {
    $tokenObj = PasswordResetToken::getToken($token);
    if (! $tokenObj) return 'Invalid or expired token';
    return View::make('forgot-password.forgot-password');
  }

  /**
  * POST /password/actions/reset-token/{token}
  */
  public function postResetPassword ($token)
  {
    $rules = [
      'password' => 'required|min:5',
      'password_conf' => 'required|same:password'
    ];
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) {
      return Response::json(
        ['message' => 'Validation failed.', 'errors' => $validator->messages()], 400
        );
    }
    $tokenObj = PasswordResetToken::getToken($token);
    if (! $tokenObj) return Response::json(['message' => 'Invalid or expired token'], 403) ;

    $user = $tokenObj->user;

    $user->password = Hash::make(Input::get('password'));
    $user->save();
    $tokenObj->status = 1;
    $tokenObj->save();
    Event::fire('password.changed', [$user]);
    return $user;
  }

  /**
  * GET /verification/email/{token}
  *
  * Helps verify email of user on registration
  */
  public function getVerifyEmail ($token)
  {
    $user = User::where('email_token', $token)->first();
    if (! $user) {
      return 'Invalid token';
    }
    $user->email_verified = 1;
    $user->email_token = null;
    $user->save();
    \Event::fire('user.email-verified', [$user]);
    return \Redirect::to('/landing')->withMessage('Email is successfully verified. Please login to continue.');
  }

  /**
  * POST /verification/phone/{otp}
  *
  * Helps verify phone_no of user on registration
  */
  public function postVerifyPhone ($otp)
  {
    if (! Input::has('email')) {
      return Response::json(['message' => 'Email is not sent.'], 400);
    }
    $user = User::where('sms_token', $otp)->where('email', Input::get('email'))->first();
    if (! $user) {
      return Response::json(['message' => 'Invalid OTP.'], 500);
    }
    $user->phone_verified = 1;
    $user->sms_token = null;
    $user->save();
    \Event::fire('user.phone-verified', [$user]);
    return ['message' => 'Phone number verified successfully.'];
  }

  /**
  * POST /verification/email/actions/resend
  *
  * Helps resend email verification token
  */
  public function postResendEmailToken ()
  {
    if (! Input::has('email')) {
      return Response::json(['message' => 'Please send an email'], 400);
    }
    $user = User::where('email', Input::get('email'))->first();
    if (! $user) {
      return Response::json(['message' => 'No user found'], 500);
    }
    $token = md5(time().'_'.$user->id);
    $user->email_token = $token;
    $user->save();
    return [];
  }

  /**
  * POST /verification/phone/actions/resend
  *
  * Helps resend phone_no verification token
  */
  public function postResendSMSToken ()
  {
    if (! Input::has('email')) {
      return Response::json(['message' => 'Please send an email'], 400);
    }
    $user = User::where('email', Input::get('email'))->first();
    if (! $user) {
      return Response::json(['message' => 'No user found'], 500);
    }
    $token = md5(time().'_'.$user->id);
    $user->sms_token = $token;
    $user->save();
    return [];
  }


  public function postResetSession ($phone_no)
  {
    if (! $phone_no) {
      return Response::json(['message' => 'Missing info'], 422);
    }
    $user = User::where('phone_no', $phone_no)->first();
     if($user->reset_session == 1)
    {
      $reset_session = User::where('phone_no',$phone_no)
      ->where('type',4)
      ->where('reset_session',1)
      ->update(['mobile_auth_code' => NULL,'web_auth_code' => NULL]);
      if($reset_session)
        {
            return Response::json(['message' => 'Session Reset Successfully'], 200);
        }
    } 
     else
        {
          return Response::json(['message' => 'Session Reset UnSuccessfully'], 400);
        }
  } 


  /* web services login*/
  public  function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}

  public function bankLogin ($data)
  {

   $guid = $this->getGUID();

   if (!empty($_SERVER["HTTP_CLIENT_IP"]))
{
 //check for ip from share internet
 $ip = $_SERVER["HTTP_CLIENT_IP"];
}
elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
{
 // Check for the Proxy User
 $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
}
else
{
 $ip = $_SERVER["REMOTE_ADDR"];
}

      //Call API for getting the freshness factor
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

     $data = [
      'phone_no' => $data['phone_no'],
      'password' =>$data['password'],
      'captcha' => $data['captcha'],
      'ipaddr'=>(string)$ip
       ];
      
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService_V3/login', $headers, $body);
  $response_data=json_decode($response->raw_body);
  //dd($response_data);
 if($response_data->status == 1)
  {
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
     Cookie::queue('password',  $data['password'], 60);
    return $response_data;

  }else
  {
    Cookie::queue('user_type',  $response_data->user_type, 60);
    Cookie::queue('parentid',  $response_data->parentid, 60);
    Cookie::queue('userid',  $response_data->userid, 60);
    Cookie::queue('mobileno',  $response_data->mobileno, 60);
    Cookie::queue('user_name',  $response_data->user_name, 60);
     Cookie::queue('password',  $data['password'], 60);

    return $response_data;
  }
  //return ['message' => 'Logged in successfully.'];

}else{
   return $response_data;
}


  }
  /*end web services*/



     public function postLoginv3($phone_no)
  {
    
  $datas=array('phone_no'=>$phone_no,'password'=>'123456','captcha'=>Input::get('captcha'));

          $user = Auth::validatev3($datas);
          //dd($user);
    //$user = Auth::validate(Input::all());
    if (! $user)
    {
      return Response::json(['message' => 'Invalid credentials'], 500);
    }
    // if ($user->email_verified == 0) {
    //   return Response::json(['message' => 'Email is not yet verified.', 'code' => 2], 403);
    // }
    if ($user->phone_verified == 0) {
      return Response::json(
          ['message' => 'Mobile no is not yet verified.', 'code' => 3, 'email' => $user->email], 403);
    }
    if ($user->status == 0)
    {
      return Response::json(
          ['message' => 'Account is deactive.', 'code' => 4, 'email' => $user->email], 403);
    }
    // if ($user->web_auth_code != NULL) {
    //   return Response::json(['message' => 'User is currently logged in from another device.', 'code' => 6], 409);
    // }

    $vendor = Vendor::where('user_id', $user->id)->first();
  

    // $user->web_auth_code = getRandomString(6);
    // $user->save();

   Auth::login($user);


 
   Event::fire('user.login', ['user' => Auth::user(), 'vendor' => $vendor]);

    // return ['status' => 1];
  return Redirect::to('http://aeps.digitalindiapayments.com');


     

    
  }

//BILLPAYMENT


     public function postLoginbillpayments($phone_no)
  {
    
  $datas=array('phone_no'=>$phone_no,'password'=>'123456','captcha'=>Input::get('captcha'));

          $user = Auth::validatev3($datas);
          //dd($user);
    //$user = Auth::validate(Input::all());
    if (! $user)
    {
      return Response::json(['message' => 'Invalid credentials'], 500);
    }
    // if ($user->email_verified == 0) {
    //   return Response::json(['message' => 'Email is not yet verified.', 'code' => 2], 403);
    // }
    if ($user->phone_verified == 0) {
      return Response::json(
          ['message' => 'Mobile no is not yet verified.', 'code' => 3, 'email' => $user->email], 403);
    }
    if ($user->status == 0)
    {
      return Response::json(
          ['message' => 'Account is deactive.', 'code' => 4, 'email' => $user->email], 403);
    }
    // if ($user->web_auth_code != NULL) {
    //   return Response::json(['message' => 'User is currently logged in from another device.', 'code' => 6], 409);
    // }

    $vendor = Vendor::where('user_id', $user->id)->first();
  

    // $user->web_auth_code = getRandomString(6);
    // $user->save();

   Auth::login($user);


 
   Event::fire('user.login', ['user' => Auth::user(), 'vendor' => $vendor]);

    // return ['status' => 1];
  return Redirect::to('http://cyberplat.digitalindiapayments.com:5000/');


     

    
  }











public function dmtloginv2tov3($phone_noo)
{


//$phone_no1 = base64_encode($phone_no);
$version =base64_encode('V3');
$serverHost1 =base64_encode('182.59.133.98');

$day=date('dMY', strtotime("+25 days"));

$datakeydec = 'eecd'.$day.'3E$5DIGI';


$Datakey=base64_encode($datakeydec);



   $data = [
      'phone_no' =>$phone_noo ,
      'AppVer' => $version,
      'ServerHost' => $serverHost1,
      'DateKey' => $Datakey,
      'portalid'=>'V2'    
      ];
      $body = Unirest\Request\Body::json($data);

//dd($body);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_PORT => "8080",
  CURLOPT_URL => "http://43.224.136.174:8080/DMTService/Loginapi",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $body,
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic cGF5ZGlwbDpEaWdpQDIwMDlfY2huXyF0",
    "Cache-Control: no-cache",
    "Content-Type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$decodejson=json_decode($response);
//dd($decodejson);
if($decodejson->status == 0)
{
     $phone_no_logout=base64_decode($phone_noo);
     $data = [
      'mobilenumber' =>$phone_no_logout];

      $body = Unirest\Request\Body::json($data);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_PORT => "8080",
  CURLOPT_URL => "http://43.224.136.174:8080/DMTService/logout",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $body,
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: 3f1278ba-ce7c-30e7-50cc-b0020d9794d7"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
$decode_response=json_decode($response);
if($decode_response->status == 1)
{

//echo '2';

//echo 'hiii'.dd($phone_no);
//$phone_no1 = base64_encode($phone_noo);
$version =base64_encode('V3');
$serverHost2 =base64_encode('182.59.133.98');

$day=date('dMY', strtotime("+25 days"));

$datakeydec1 = 'eecd'.$day.'3E$5DIGI';


$Datakey1=base64_encode($datakeydec1);



   $data = [
      'phone_no' =>$phone_noo,
      'AppVer' => $version,
      'ServerHost' => $serverHost2,
      'DateKey' => $Datakey1,
      'portalid'=>'V2' 
    
      ];
      $body = Unirest\Request\Body::json($data);
//dd($body);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_PORT => "8080",
  CURLOPT_URL => "http://43.224.136.174:8080/DMTService/Loginapi",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $body,
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic cGF5ZGlwbDpEaWdpQDIwMDlfY2huXyF0",
    "Cache-Control: no-cache",
    "Content-Type: application/json",
    "Postman-Token: aa83e4ec-400c-48d4-af8d-cf4e07e281cc"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$decodejson=json_decode($response);

            if($decodejson->status==1)
            { $bankLoginResponse = $this->postLogin_v3dmt();
            Cookie::queue('user',  $decodejson->sessiontoken, 60);
                Cookie::queue('userid',  $decodejson->userid, 60);
                Cookie::queue('bcagent',  $decodejson->bcagent, 60);
                Cookie::queue('user_type',  $decodejson->user_type, 60);
                Cookie::queue('parentid',  $decodejson->parentid, 60);
                Cookie::queue('mobileno',  $decodejson->mobileno, 60);
                Cookie::queue('user_name',  $decodejson->user_name, 60);
                Cookie::queue('session_timeout',  $decodejson->session_timeout, 60);
                Cookie::queue('portalid',  'v3', 60);

           // dd($decodejson);

return Redirect::to('http://paydmt.digitalindiapayments.com:8021');
             }else
            {

              echo '<div id="header" style="border-bottom: 2px #00d7ff solid;background-color: white;">
    <ul class="container grid">
        <li>
          <div class="img img-logo" alt="Paytm Payments" title="Paytm Payments"><center>
          <img src="http://aeps.digitalindiapayments.com/images/cinqueterre.png" style="height: 53px;" alt="" title=""></center>
          </div>
        </li>
      </ul>
      <div class="clear"></div>
  </div>
  <div class="container" style="margin-left: 438px;">
    <div class="gray_container">
      <div class="white_container width-pad">
        <div class="fr"><img src="https://static1.paytm.in/1.4/images/oops_image.png" alt="" title=""></div>
        <div class="fl">
          <div class="pad15">
                      <div class="txt f18">
                        <b class="b">Dmt Login failed due to any of these reasons:</b><br>
                           <ul class="aln">
                               <li>Session expired due to inactivity</li>
                               <li>Our system encountered an obstacle</li>
                                <li>Kindly contact Admin for login issue.</li>
                                <li>Email Contact :- dmt.support@digitalindiapayments.com</li>
                           </ul>
                      </div>
                      
                      
                  </div>
                </div>
                                    
              <div class="clear"></div>
              <br>
              <br>
              <br>
              
      </div>
    </div>
    </div>';
            }
}else
{
  echo '<div id="header" style="border-bottom: 2px #00d7ff solid;background-color: white;">
    <ul class="container grid">
        <li>
          <div class="img img-logo" alt="Paytm Payments" title="Paytm Payments"><center>
          <img src="http://aeps.digitalindiapayments.com/images/cinqueterre.png" style="height: 53px;" alt="" title=""></center>
          </div>
        </li>
      </ul>
      <div class="clear"></div>
  </div>
  <div class="container" style="margin-left: 438px;">
    <div class="gray_container">
      <div class="white_container width-pad">
        <div class="fr"><img src="https://static1.paytm.in/1.4/images/oops_image.png" alt="" title=""></div>
        <div class="fl">
          <div class="pad15">
                      <div class="txt f18">
                        <b class="b">Dmt Login failed due to any of these reasons:</b><br>
                           <ul class="aln">
                               <li>Session expired due to inactivity</li>
                               <li>Our system encountered an obstacle</li>
                                <li>Kindly contact Admin for login issue.</li>
                                 <li>Email Contact :- dmt.support@digitalindiapayments.com</li>
                           </ul>
                      </div>
                      
                      
                  </div>
                </div>
                                    
              <div class="clear"></div>
              <br>
              <br>
              <br>
              
      </div>
    </div>
    </div>';
}


}else
{
     $bankLoginResponse = $this->postLogin_v3dmt();
Cookie::queue('user',  $decodejson->sessiontoken, 60);
    Cookie::queue('userid',  $decodejson->userid, 60);
    Cookie::queue('bcagent',  $decodejson->bcagent, 60);
    Cookie::queue('user_type',  $decodejson->user_type, 60);
    Cookie::queue('parentid',  $decodejson->parentid, 60);
    Cookie::queue('mobileno',  $decodejson->mobileno, 60);
    Cookie::queue('user_name',  $decodejson->user_name, 60);
    Cookie::queue('session_timeout',  $decodejson->session_timeout, 60);
  Cookie::queue('portalid',  'v3', 60);
  return Redirect::to('http://paydmt.digitalindiapayments.com:8021');
}


}

//dmt login

  public function postLogin_v3dmt()
  {
  

  $datas=array('phone_no'=>'9999900000','password'=>'123456','captcha'=>Input::get('captcha'));

          $user = Auth::validate($datas);
    //$user = Auth::validate(Input::all());
    if (! $user)
    {
      return Response::json(['message' => 'Invalid credentials'], 500);
    }
    // if ($user->email_verified == 0) {
    //   return Response::json(['message' => 'Email is not yet verified.', 'code' => 2], 403);
    // }
    if ($user->phone_verified == 0) {
      return Response::json(
          ['message' => 'Mobile no is not yet verified.', 'code' => 3, 'email' => $user->email], 403);
    }
    if ($user->status == 0)
    {
      return Response::json(
          ['message' => 'Account is deactive.', 'code' => 4, 'email' => $user->email], 403);
    }
    if ($user->web_auth_code != NULL) {
      return Response::json(['message' => 'User is currently logged in from another device.', 'code' => 6], 409);
    }

    $vendor = Vendor::where('user_id', $user->id)->first();
  

    //$user->web_auth_code = getRandomString(6);
    //$user->save();

    Auth::login($user);

    $settlement_flag = Settlement::where('user_id', $user->id)->first();
     if(!$settlement_flag){
      Session::put('settlement',1);
     }else{
      Session::forget('settlement');
     }
     

    // $bankLoginResponse = $this->bankLogin(Input::all());
 
   Event::fire('user.login', ['user' => Auth::user(), 'vendor' => $vendor]);
    return ['status' => 1];
  

     
    
}


}