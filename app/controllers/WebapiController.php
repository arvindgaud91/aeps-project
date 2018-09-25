<?php
use Acme\Auth\Auth;
use Acme\SMS\SMS;

/**
*  A controller that deals with action APIs
*/
class WebapiController extends BaseController
{
	


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

	public function postlogin ()
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
      'phone_no' => '9952777568',
      'password' => '123456',
      'captcha' => '123',
      'ipaddr'=>$ip
       ];
       
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://43.224.136.144:8080/DMTService/login', $headers, $body);
Session::push('user',$response->raw_body);
 //dd($response);
return ['message' => 'Logged in successfully.'];
	}


  public function remitter()
  {
$headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

     $data = [
      'remittermobilenumber' => '9029197048',
      'remittername' => 'dipl@123',
      'remitteraddress' => '123',
      'pincode'=>'40072',
      'cityname'=>'mumbai',
      'statename'=>'maha',
      'lremitteraddress'=>'fsfs',
      'lpincode'=>'40125',
      'lstatename'=>'gfgfh',
      'lcityname'=>'mumnbai'
       ];

     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://192.168.1.101:8080/DMTService/Remitteraddrequest', $headers, $body);

  dd($response);
  }

  public function benf()
  {
$headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

     $data = [
      'remitterid' => '9029197048',
      'beneficiaryname' => 'dipl@123',
      'beneficiarymobilenumber' => '123',
      'beneficiaryemailid'=>'40072',
      'relationshipid'=>'mumbai',
      'ifscode'=>'maha',
      'accountnumber'=>'fsfs',
      'flag'=>'40125',
      
       ];

         
     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://192.168.1.101:8080/DMTService/addremitter', $headers, $body);

  dd($response);
  }
}	
