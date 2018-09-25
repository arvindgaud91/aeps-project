<?php

use Acme\Auth\Auth;
use Carbon\Carbon;
use Acme\Helper\Rabbit;
use Illuminate\Http\Request;


class TicketController extends BaseController {

  public function  createticket(){
   // dd(Input::all());
   $vendor = Vendor::where('user_id', Auth::user()->id)->first();
 $getGUID=$this->getGUID();
   $postdata=array('type'=>array('code'=>$vendor->ticket_token),'subject'=>Input::get('title'),'publicReply'=>array('body'=>Input::get('description')

   ));

 $curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => "http://lserver218-ind.megavelocity.net/api/v1/tickets",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>json_encode($postdata),
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
     "token:".$vendor->ticket_token."",
    "appinstancecode:".$getGUID.""
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
dd($response);
curl_close($curl);
      

    }

 public function  getticket()
    {
       //if (! Auth::user()) return Response::json("Invalid Token", 444);
        // $postdata=array('assignee'=>array('code'=>'fssscdsds','profile'=>array('')



        // )

        // );



   $curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => "http://dipl.dev.green-earth.online/api/v1/tickets",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  //CURLOPT_POSTFIELDS =>json_encode($data),
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "token:a856d9d7-ede0-4627-864e-8b99ad6a5609",
    "appinstancecode:hfdskjhf77-45o76hjds09375-98748hfjh"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
dd($response);
curl_close($curl);
      

    }


    
 public function getcategories()
{

   $curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => "http://demo.dev.green-earth.online/api/v1/categories",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  //CURLOPT_POSTFIELDS =>json_encode($data),
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "token:a856d9d7-ede0-4627-864e-8b99ad6a5609",
    "appinstancecode:hfdskjhf77-45o76hjds09375-98748hfjh"

  ),
));

$response = curl_exec($curl);
$result = json_decode($response,TRUE);

$err = curl_error($curl);
curl_close($curl);
return View::make('ticket.create-ticket')
        ->with('result',$result);

      


}
public function getGUID(){
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
public function ticketlogin()
{

$curl = curl_init();
 $vendor = User::where('id', Auth::user()->id)->first();
$arraydata=array('agentId' =>$vendor->id,'email' =>$vendor->email,'firstName' =>$vendor->name,'lastName' =>'','mobile'=>$vendor->phone_no);
//dd(json_encode($arraydata));

 
$getGUID=$this->getGUID();
//dd($getGUID);
 $remove=str_replace("{",'',$getGUID);
 $removee=str_replace("}",'',$remove);
  curl_setopt_array($curl, array(
  CURLOPT_URL => "http://support.digitalindiapayments.com/api/v1/dipl/agent",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_FOLLOWLOCATION=>true,
  CURLOPT_POSTFIELDS =>json_encode($arraydata),
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "token:".$vendor->ticket_token."",
    "appinstancecode:".$removee.""

  ),
));

$response = curl_exec($curl);
//dd($response);
$array=json_decode($response);
$err = curl_error($curl);
curl_close($curl);

if($array->errorCode == 200)
{
  $vendor = Vendor::where('user_id', Auth::user()->id)->first();
  $vendor->ticket_token=$array->data->token;
   $vendor->ticket_userId=$array->data->userId;
   $vendor->appinstancecode=$removee;
   $vendor->save();
//setcookie('at_token', $array->data->token, time() + (86400 * 30));
    //$dffff=Auth::cokkies($array->data->token,$getGUID);
//setcookie('at_appCode', $removee, time() + (86400 * 30));
  
     
  return Response::json([
      // @todo confirm with amol
      "at_token" => $array->data->token,
      "at_appCode" =>$removee], 200);
   
}else
{
$msg='Something Went Wrong..!';
return Response::json($msg, 422);

}


}

    

 }  