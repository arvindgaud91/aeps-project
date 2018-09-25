<?php
require 'bootstrap/autoload.php';
$app = require 'bootstrap/start.php';
echo "started";
$app->boot();

use Acme\Helper\Rabbit;
use Acme\Contracts\SubscriptionHandler;
use Acme\ISO8583\ISO8583;
use Carbon\Carbon;

/**
 *
 */
class MATM extends SubscriptionHandler
{
  private $iso127Log;
  private $isoMessageLog;

  public function handle ($payload)
  {
    echo "in handle \n";
    // return $this->acknowledgeSuccess();
    try {
     if (! isset($payload->action))
        $this->acknowledgeSuccess();

      if ($payload->action == 'balance_enquiry') $this->balance($payload);
      if ($payload->action == 'deposit') $this->deposit($payload);
      if ($payload->action == 'withdraw') $this->withdraw($payload);

      return $this->acknowledgeSuccess();
    } catch (Exception $e) {
      $tx = AepsTransaction::find($payload->transaction_id);
      if (! $tx)
        return $this->acknowledgeSuccess();
      $tx->status = 4;
     // $tx->result = 0;//queuefail chg to success update by pradip and rahul 
      $tx->result = 1;
      // @todo find better way
      echo $e->getMessage();
      $tx->remarks = 'queuefail';
      $tx->aadhar_no = substr_replace($tx->aadhar_no, '********', 0, 8);
      $tx->save();
      if ($payload->action == 'deposit')
      {
        $transaction = AepsTransaction::find($payload->transaction_id);
        $vendor = Vendor::where('csr_id', $payload->csr_id)->first();
          if($vendor->master_wallet_id == 1) 
       {
        $distributordetails = vendor::where('user_id',$vendor->parent_id)->first();
        $distributordetails->balance = $this->updateWalletmuthoot($vendor, $transaction);
        $distributordetails->save();

       }else 
       {
          $vendor->balance = $this->updateWallet($vendor, $transaction);
          $vendor->save();

       }

      } 
      return $this->acknowledgeSuccess();
    }
  }

  private function balance ($payload)
  {
    // dd($payload);
      if($payload->device_service == 1)
      {

               
            echo "in balance rd \n";
            $transaction = AepsTransaction::find($payload->transaction_id);
            if ($transaction->status == 3 || $transaction->status == 4) return; // Do not send same transaction twice.
            if (Carbon::parse($transaction->updated_at)->diffInSeconds(Carbon::now('Asia/Kolkata')) > 15) {
              $transaction->status = 4;
              $transaction->result = 0;
              $transaction->remarks = 'Transaction Expired';
              $transaction->save();
              return;
            }
            $transaction->status = 1;
           // $strToBin=$this->strToBin($payload->pid);
          //$binary_pid = base64_decode($strToBin);
           // $hex_pid = bin2hex($binary_pid);
            // $pid_data=str_pad(strlen($hex_pid), 3,'0', STR_PAD_LEFT);
            //$fixdata=str_pad(25,3,'0',STR_PAD_LEFT);
            // $addition_data=$pid_data +$fixdata;
             //$pid_final=str_pad($addition_data,3,'0',STR_PAD_LEFT);
            date_default_timezone_set('Asia/Kolkata');
           // $hexa_pid_127=bin2hex($payload->pid);
          
        $length_126 = strlen('001009nnnyFMRnn'.'008001P'.'009016'.'00000000'.$payload->device_id.'010'.str_pad(strlen($payload->dpId),3,'0', STR_PAD_LEFT).$payload->dpId.'011'.str_pad(strlen($payload->rdsId),3,'0', STR_PAD_LEFT).$payload->rdsId.'012'.str_pad(strlen($payload->rdsVer),3,'0', STR_PAD_LEFT).$payload->rdsVer.'013'.str_pad(strlen($payload->dc),3,'0', STR_PAD_LEFT).$payload->dc.'014'.str_pad(strlen($payload->mi),3,'0', STR_PAD_LEFT).$payload->mi.'015003FPD');


            $mcLength = strlen($payload->mc);
            $mcOverflow = $mcLength > 993 ? true : false;
            $elements = [
              2 => '19'.$payload->bank_iin.'0'.$payload->aadhar_no,
              3 => '310000',
              4 => sprintf('%010u', $payload->amount).'00',
              7 => date('mdHis'),
              11 => $payload->stan,
              12 => date('His'),
              13 => date('md'),
              18 => "6012",
              41 => $payload->device_id,
              49 => '356',
              123 => '015210101213146000',
              126 => $length_126.'001009nnnyFMRnn'.'008001X'.'009016'.'00000000'.$payload->device_id.'010'.str_pad(strlen($payload->dpId),3,'0', STR_PAD_LEFT).$payload->dpId.'011'.str_pad(strlen($payload->rdsId),3,'0', STR_PAD_LEFT).$payload->rdsId.'012'.str_pad(strlen($payload->rdsVer),3,'0', STR_PAD_LEFT).$payload->rdsVer.'013'.str_pad(strlen($payload->dc),3,'0', STR_PAD_LEFT).$payload->dc.'014'.str_pad(strlen($payload->mi),3,'0', STR_PAD_LEFT).$payload->mi.'015003FPD',
              127 =>  '00'.''.(strlen($payload->pid)+25).'00000400800000000'.''.str_pad(strlen($payload->pid),3,'0', STR_PAD_LEFT).$payload->pid.'9117'
            ];
          
          

          
          
            $this->iso127Log = '00'.''.(strlen($payload->pid)+25).'00000400800000000'.''.str_pad(strlen($payload->pid),3,'0', STR_PAD_LEFT).$payload->pid.'9117';


           

            // Check size of mc attribute and split accordingly
            if ($mcOverflow) {
              $length = $mcLength+0;
              $mcLength_109=$mcLength-999;
              $elements[108] = '999mc'.$length.substr($payload->mc, 0, 993);
              $mcLength -= 993;
              $elements[109] = $mcLength > 999 ? $mcLength_109.substr($payload->mc, 993, 999) : $mcLength.substr($payload->mc, 993, $mcLength);
              $mcLength -= 999;
              if ($mcLength > 0)
                $elements[110] = $mcLength.substr($payload->mc, 1992, $mcLength);
            }
            else
              $elements[108] = 'mc'.$mcLength.substr($payload->mc, 0, $mcLength);
            $params = [
              'mti' => '0200',
              'elements' => $elements
            ];
            
           // dd($params);
            
            $isoFactory = new ISO8583();
            $isoMessage = $isoFactory->create($params);

            $paramsLog = $params;
            $paramsLog['elements']['127'] = $this->iso127Log;
            $this->isoMessageLog = $isoFactory->create($paramsLog);

            $transaction->status = 2;

            $rawResponse = $this->transact($payload, $isoMessage);
            \Log::info('iso8583Message: '.$this->isoMessageLog);
            $transaction->status = 3;
            $transaction->save();
            \Log::info('Bank Response: '.json_encode($rawResponse));

            $vendor = Vendor::where('csr_id', $payload->csr_id)->first();
            $vendor->freshness_factor = $rawResponse->nextFreshnessFactor;
            $vendor->save();

            $transaction->bank_response_code = $rawResponse->responseCode;

            if ($rawResponse->responseCode != '00') {
              $transaction->status = 4;
              $transaction->result = 0;
            }

            if (isset($rawResponse->object)) {
              $isoParsed = $isoFactory->parse($rawResponse->object->iso8583Message);
              $transaction->status = 4;
              $transaction->result_code = $isoParsed[39];
              $transaction->result = $transaction->result_code == '00' ? 1 : 0;
              $transaction->balance = isset($isoParsed[54]) ? $this->fetchRblAmount($isoParsed[54]) : 0;
              $transaction->rrn = isset($isoParsed[37]) ? $isoParsed[37] : ''; // @TODO: Change to null in db and here
              $transaction->uidai_auth_code = isset($isoParsed[48]) ? $isoParsed[48] : '';
              echo $transaction->result;

              if ($transaction->result == 1) {
                $vendor = Vendor::where('csr_id', $payload->csr_id)->lockForUpdate()->first();
                $vendor->balance = $this->updateWallet($vendor, $transaction);
                $vendor->save();

                if(isset($payload->remark1) && isset($payload->remark2)){
                 $muthootResponse=$this->muthootDataSend($payload);
      
                 }
              }
            }

            $transaction->save();

     }


      if($payload->device_service == 0)
      {

    echo "in balance with out rd \n";
    $transaction = AepsTransaction::find($payload->transaction_id);
    if ($transaction->status == 3 || $transaction->status == 4) return; // Do not send same transaction twice.
    if (Carbon::parse($transaction->updated_at)->diffInSeconds(Carbon::now('Asia/Kolkata')) > 15) {
      $transaction->status = 4;
      $transaction->result = 0;
      $transaction->remarks = 'Transaction Expired';
      $transaction->save();
      return;
    }
    $transaction->status = 1;
    date_default_timezone_set('Asia/Kolkata');
    $elements = [
      2 => '19'.$payload->bank_iin.'0'.$payload->aadhar_no,
      3 => '310000',
      4 => sprintf('%010u', $payload->amount).'00',
      7 => date('mdHis'),
      11 => $payload->stan,
      12 => date('His'),
      13 => date('md'),
      18 => "6012",
      41 => $payload->device_id,
      49 => '356',
      123 => '015210101213146000',
      127 => $this->fetchDE127($payload->fingerprint)
    ];
    $params = [
      'mti' => '0200',
      'elements' => $elements
    ];
    $isoFactory = new ISO8583();
    $isoMessage = $isoFactory->create($params);

    $paramsLog = $params;
    $paramsLog['elements']['127'] = $this->iso127Log;
    $this->isoMessageLog = $isoFactory->create($paramsLog);

    $transaction->status = 2;

    $rawResponse = $this->transact($payload, $isoMessage);
    \Log::info('iso8583Message: '.$this->isoMessageLog);
    $transaction->status = 3;
    $transaction->save();
    \Log::info('Bank Response: '.json_encode($rawResponse));

    $vendor = Vendor::where('csr_id', $payload->csr_id)->first();
    $vendor->freshness_factor = $rawResponse->nextFreshnessFactor;
    $vendor->save();

    

    $transaction->bank_response_code = $rawResponse->responseCode;
    
    

    if ($rawResponse->responseCode != '00') {
      $transaction->status = 4;
      $transaction->result = 0;
    }

    if (isset($rawResponse->object)) {
      $isoParsed = $isoFactory->parse($rawResponse->object->iso8583Message);
      $transaction->status = 4;
      $transaction->result_code = $isoParsed[39];
      $transaction->result = $transaction->result_code == '00' ? 1 : 0;
      $transaction->balance = isset($isoParsed[54]) ? $this->fetchRblAmount($isoParsed[54]) : 0;
      $transaction->rrn = isset($isoParsed[37]) ? $isoParsed[37] : ''; // @TODO: Change to null in db and here
      $transaction->uidai_auth_code = isset($isoParsed[48]) ? $isoParsed[48] : '';
      

      echo $transaction->result;

      if ($transaction->result == 1) 
      {
        $vendor = Vendor::where('csr_id', $payload->csr_id)->lockForUpdate()->first();

        if($vendor->master_wallet_id == 1) 
       {
        $distributordetails = vendor::where('user_id',$vendor->parent_id)->first();
        $distributordetails->balance = $this->updateWalletmuthoot($vendor, $transaction);
        $distributordetails->save();

       }else 
       {
          $vendor->balance = $this->updateWallet($vendor, $transaction);
          $vendor->save();

       } 
		
    		if(isset($payload->remark1) && isset($payload->remark2)){
    		  $muthootResponse=$this->muthootDataSend($payload);
    		  //dd($muthootResponse);
    		}
      }
    }

    $transaction->save();
  }

  }

  private function deposit ($payload)
  {


     if($payload->device_service == 1)
     {

      echo "in deposit rd \n";
    $transaction = AepsTransaction::find($payload->transaction_id);
    if ($transaction->status == 3 || $transaction->status == 4) return; // Do not send same transaction twice.
    if (Carbon::parse($transaction->updated_at)->diffInSeconds(Carbon::now('Asia/Kolkata')) > 15) {
      $transaction->status = 4;
      $transaction->result = 0;
      $transaction->remarks = 'Transaction Expired';
      $transaction->save();
      return;
    }
    $transaction->status = 1;

    date_default_timezone_set('Asia/Kolkata');
      
$length_126 = strlen('001009nnnyFMRnn'.'008001P'.'009016'.'00000000'.$payload->device_id.'010'.str_pad(strlen($payload->dpId),3,'0', STR_PAD_LEFT).$payload->dpId.'011'.str_pad(strlen($payload->rdsId),3,'0', STR_PAD_LEFT).$payload->rdsId.'012'.str_pad(strlen($payload->rdsVer),3,'0', STR_PAD_LEFT).$payload->rdsVer.'013'.str_pad(strlen($payload->dc),3,'0', STR_PAD_LEFT).$payload->dc.'014'.str_pad(strlen($payload->mi),3,'0', STR_PAD_LEFT).$payload->mi.'015003FPD');


    $mcLength = strlen($payload->mc);
    $mcOverflow = $mcLength > 993 ? true : false;
    $elements = [
      2 => '19'.$payload->bank_iin.'0'.$payload->aadhar_no,
      3 => '210000',
      4 => sprintf('%010u', $payload->amount).'00',
      7 => date('mdHis'),
      11 => $payload->stan,
      12 => date('His'),
      13 => date('md'),
      18 => "6012",
      41 => $payload->device_id,
      49 => '356',
      123 => '015210101213146000',
      126 => $length_126.'001009nnnyFMRnn'.'008001X'.'009016'.'00000000'.$payload->device_id.'010'.str_pad(strlen($payload->dpId),3,'0', STR_PAD_LEFT).$payload->dpId.'011'.str_pad(strlen($payload->rdsId),3,'0', STR_PAD_LEFT).$payload->rdsId.'012'.str_pad(strlen($payload->rdsVer),3,'0', STR_PAD_LEFT).$payload->rdsVer.'013'.str_pad(strlen($payload->dc),3,'0', STR_PAD_LEFT).$payload->dc.'014'.str_pad(strlen($payload->mi),3,'0', STR_PAD_LEFT).$payload->mi.'015003FPD',
      127 =>  '00'.''.(strlen($payload->pid)+25).'00000400800000000'.''.str_pad(strlen($payload->pid),3,'0', STR_PAD_LEFT).$payload->pid.'9117'
    ];
  
  

  
  
    $this->iso127Log = '00'.''.(strlen($payload->pid)+25).'00000400800000000'.''.str_pad(strlen($payload->pid),3,'0', STR_PAD_LEFT).$payload->pid.'9117';


   

    // Check size of mc attribute and split accordingly
    if ($mcOverflow) {
      $length = $mcLength+0;
      $mcLength_109=$mcLength-999;
      $elements[108] = '999mc'.$length.substr($payload->mc, 0, 993);
      $mcLength -= 993;
      $elements[109] = $mcLength > 999 ? $mcLength_109.substr($payload->mc, 993, 999) : $mcLength.substr($payload->mc, 993, $mcLength);
      $mcLength -= 999;
      if ($mcLength > 0)
        $elements[110] = $mcLength.substr($payload->mc, 1992, $mcLength);
    }
    else
      $elements[108] = 'mc'.$mcLength.substr($payload->mc, 0, $mcLength);
    $params = [
      'mti' => '0200',
      'elements' => $elements
    ];
    $isoFactory = new ISO8583();
    $isoMessage = $isoFactory->create($params);
    $paramsLog = $params;
    $paramsLog['elements']['127'] = $this->iso127Log;
    $this->isoMessageLog = $isoFactory->create($paramsLog);

    $transaction->status = 2;
    \Log::info('iso8583Message: '.$this->isoMessageLog);

    $rawResponse = $this->transact($payload, $isoMessage);
    $transaction->status = 3;
    $transaction->save();
    \Log::info('Bank Response: '.json_encode($rawResponse));

    $vendor = Vendor::where('csr_id', $payload->csr_id)->first();
    $vendor->freshness_factor = $rawResponse->nextFreshnessFactor;
    $vendor->save();

    $transaction->bank_response_code = $rawResponse->responseCode;

    if ($rawResponse->responseCode != '00') {
      $transaction->status = 4;
      $transaction->result = 0;
    }

    if (isset($rawResponse->object)) {
      $isoParsed = $isoFactory->parse($rawResponse->object->iso8583Message);
      $transaction->status = 4;
      $transaction->result_code = $isoParsed[39];
      $transaction->result = $transaction->result_code == '00' || $transaction->result_code == '91' || $transaction->result_code == 'DD' || $transaction->result_code == 'DS' ? 1 : 0;
      $transaction->balance = isset($isoParsed[54]) ? $this->fetchRblAmount($isoParsed[54]) : 0;
      $transaction->rrn = isset($isoParsed[37]) ? $isoParsed[37] : ''; // @TODO: Change to null in db and here
      $transaction->uidai_auth_code = isset($isoParsed[48]) ? $isoParsed[48] : '';
      echo $transaction->result;
    }

    if ($transaction->result == 1) 
    {
       if($vendor->master_wallet_id == 1) 
       {
        $distributordetails = vendor::where('user_id',$vendor->parent_id)->first();
        $distributordetails->balance = $this->updateWalletmuthoot($vendor, $transaction);
        $distributordetails->save();

       }else 
       {
          $vendor->balance = $this->updateWallet($vendor, $transaction);
          $vendor->save();

       }

      if(isset($payload->remark1) && isset($payload->remark2))
      {
      $muthootResponse=$this->muthootDataSend($payload);
      
      }



    }

    $transaction->save();


     }


    if($payload->device_service == 0)
    {
    echo "in deposit with out rd \n";
    $transaction = AepsTransaction::find($payload->transaction_id);
    if ($transaction->status == 3 || $transaction->status == 4) return; // Do not send same transaction twice.
    if (Carbon::parse($transaction->updated_at)->diffInSeconds(Carbon::now('Asia/Kolkata')) > 15) {
      $transaction->status = 4;
      $transaction->result = 0;
      $transaction->remarks = 'Transaction Expired';
      $transaction->save();
      return;
    }
    $transaction->status = 1;

    date_default_timezone_set('Asia/Kolkata');
    $elements = [
    2 => '19'.$payload->bank_iin.'0'.$payload->aadhar_no,
    3 => '210000',
    4 =>  sprintf('%010u', $payload->amount).'00',
    7 => date('mdHis'),
    11 => $payload->stan,
    12 => date('His'),
    13 => date('md'),
    18 => "6012",
    41 => $payload->device_id,
    49 => '356',
    123 => '015210101213146000',
    127 => $this->fetchDE127($payload->fingerprint)
    ];
    $params = [
    'mti' => '0200',
    'elements' => $elements
    ];

    $isoFactory = new ISO8583();
    $isoMessage = $isoFactory->create($params);
    $paramsLog = $params;
    $paramsLog['elements']['127'] = $this->iso127Log;
    $this->isoMessageLog = $isoFactory->create($paramsLog);

    $transaction->status = 2;
    \Log::info('iso8583Message: '.$this->isoMessageLog);

    $rawResponse = $this->transact($payload, $isoMessage);
    $transaction->status = 3;
    $transaction->save();
    \Log::info('Bank Response: '.json_encode($rawResponse));


 $vendor = Vendor::where('csr_id', $payload->csr_id)->first();
 $vendor->freshness_factor = $rawResponse->nextFreshnessFactor;
 $vendor->save();

    
    $transaction->bank_response_code = $rawResponse->responseCode;


    if ($rawResponse->responseCode != '00') {
      $transaction->status = 4;
      $transaction->result = 0;
    }


    if (isset($rawResponse->object)) {
      $isoParsed = $isoFactory->parse($rawResponse->object->iso8583Message);
      $transaction->status = 4;
      $transaction->result_code = $isoParsed[39];
      $transaction->result = $transaction->result_code == '00' ? 1 : 0;
      $transaction->balance = isset($isoParsed[54]) ? $this->fetchRblAmount($isoParsed[54]) : 0;
      $transaction->rrn = isset($isoParsed[37]) ? $isoParsed[37] : ''; // @TODO: Change to null in db and here
      $transaction->uidai_auth_code = isset($isoParsed[48]) ? $isoParsed[48] : '';
      echo $transaction->result;
    }

    if ($transaction->result == 1) {
       if($vendor->master_wallet_id == 1) 
       {
        $distributordetails = vendor::where('user_id',$vendor->parent_id)->first();
        $distributordetails->balance = $this->updateWalletmuthoot($vendor, $transaction);
        $distributordetails->save();

       }else 
       {
          $vendor->balance = $this->updateWallet($vendor, $transaction);
          $vendor->save();

       }
	  
	  if(isset($payload->remark1) && isset($payload->remark2))
    {
      $muthootResponse=$this->muthootDataSend($payload);
      
     }
    }


    $transaction->save();
  }
}

  private function withdraw ($payload)
  {

    if($payload->device_service == 1)
    {
  echo "in withdraw rd \n";
    $transaction = AepsTransaction::find($payload->transaction_id);
    if ($transaction->status == 3 || $transaction->status == 4) return; // Do not send same transaction twice.
    if (Carbon::parse($transaction->updated_at)->diffInSeconds(Carbon::now('Asia/Kolkata')) > 15) {
      $transaction->status = 4;
      $transaction->result = 0;
      $transaction->remarks = 'Transaction Expired';
      $transaction->save();
      return;
    }
    $transaction->status = 1;

    date_default_timezone_set('Asia/Kolkata');
      
$length_126 = strlen('001009nnnyFMRnn'.'008001P'.'009016'.'00000000'.$payload->device_id.'010'.str_pad(strlen($payload->dpId),3,'0', STR_PAD_LEFT).$payload->dpId.'011'.str_pad(strlen($payload->rdsId),3,'0', STR_PAD_LEFT).$payload->rdsId.'012'.str_pad(strlen($payload->rdsVer),3,'0', STR_PAD_LEFT).$payload->rdsVer.'013'.str_pad(strlen($payload->dc),3,'0', STR_PAD_LEFT).$payload->dc.'014'.str_pad(strlen($payload->mi),3,'0', STR_PAD_LEFT).$payload->mi.'015003FPD');



    $mcLength = strlen($payload->mc);
    $mcOverflow = $mcLength > 993 ? true : false;
    $elements = [
      2 => '19'.$payload->bank_iin.'0'.$payload->aadhar_no,
      3 => '010000',
      4 => sprintf('%010u', $payload->amount).'00',
      7 => date('mdHis'),
      11 => $payload->stan,
      12 => date('His'),
      13 => date('md'),
      18 => "6012",
      41 => $payload->device_id,
      49 => '356',
      123 => '015210101213146000',
      126 => $length_126.'001009nnnyFMRnn'.'008001X'.'009016'.'00000000'.$payload->device_id.'010'.str_pad(strlen($payload->dpId),3,'0', STR_PAD_LEFT).$payload->dpId.'011'.str_pad(strlen($payload->rdsId),3,'0', STR_PAD_LEFT).$payload->rdsId.'012'.str_pad(strlen($payload->rdsVer),3,'0', STR_PAD_LEFT).$payload->rdsVer.'013'.str_pad(strlen($payload->dc),3,'0', STR_PAD_LEFT).$payload->dc.'014'.str_pad(strlen($payload->mi),3,'0', STR_PAD_LEFT).$payload->mi.'015003FPD',
      127 =>  '00'.''.(strlen($payload->pid)+25).'00000400800000000'.''.str_pad(strlen($payload->pid),3,'0', STR_PAD_LEFT).$payload->pid.'9117'
    ];
  
  

  
  
    $this->iso127Log = '00'.''.(strlen($payload->pid)+25).'00000400800000000'.''.str_pad(strlen($payload->pid),3,'0', STR_PAD_LEFT).$payload->pid.'9117';


   

    // Check size of mc attribute and split accordingly
    if ($mcOverflow) {
      $length = $mcLength+0;
      $mcLength_109=$mcLength-999;
      $elements[108] = '999mc'.$length.substr($payload->mc, 0, 993);
      $mcLength -= 993;
      $elements[109] = $mcLength > 999 ? $mcLength_109.substr($payload->mc, 993, 999) : $mcLength.substr($payload->mc, 993, $mcLength);
      $mcLength -= 999;
      if ($mcLength > 0)
        $elements[110] = $mcLength.substr($payload->mc, 1992, $mcLength);
    }
    else
      $elements[108] = 'mc'.$mcLength.substr($payload->mc, 0, $mcLength);
    $params = [
      'mti' => '0200',
      'elements' => $elements
    ];

    $isoFactory = new ISO8583();
    $isoMessage = $isoFactory->create($params);

    $paramsLog = $params;
    $paramsLog['elements']['127'] = $this->iso127Log;
    $this->isoMessageLog = $isoFactory->create($paramsLog);

    $transaction->status = 2;
    \Log::info('iso8583Message: '.$this->isoMessageLog);

    $rawResponse = $this->transact($payload, $isoMessage);
    $transaction->status = 3;
    $transaction->save();
    \Log::info(json_encode($rawResponse));

    $vendor = Vendor::where('csr_id', $payload->csr_id)->first();
    $vendor->freshness_factor = $rawResponse->nextFreshnessFactor;
    $vendor->save();

    $transaction->bank_response_code = $rawResponse->responseCode;

    if ($rawResponse->responseCode != '00') {
      $transaction->status = 4;
      $transaction->result = 0;
    }

    if (isset($rawResponse->object)) {
      $isoParsed = $isoFactory->parse($rawResponse->object->iso8583Message);
      $transaction->status = 4;
      $transaction->result_code = $isoParsed[39];
      $transaction->result = $transaction->result_code == '00' ? 1 : 0;
      $transaction->balance = isset($isoParsed[54]) ? $this->fetchRblAmount($isoParsed[54]) : 0;
      $transaction->rrn = isset($isoParsed[37]) ? $isoParsed[37] : ''; // @TODO: Change to null in db and here
      $transaction->uidai_auth_code = isset($isoParsed[48]) ? $isoParsed[48] : '';
      echo $transaction->result;
    }
//resutlt success than update wallet 
    if ($transaction->result == 1) 
    {
      
      if($vendor->master_wallet_id == 1) 
       {
        $distributordetails = vendor::where('user_id',$vendor->parent_id)->first();
        $distributordetails->balance = $this->updateWalletmuthoot($vendor, $transaction);
        $distributordetails->save();
       // dd($distributordetails);

       }else 
       {
         $vendor = Vendor::where('csr_id', $payload->csr_id)->lockForUpdate()->first();

          $uadate=$this->updateWallet($vendor, $transaction);
          $vendor->balance=$uadate;
          $vendor->save();

       } 

  	  if(isset($payload->remark1) && isset($payload->remark2))
      {
        $muthootResponse=$this->muthootDataSend($payload);
        //dd($muthootResponse);
      }


    }
 
  
    $transaction->save();

    }




    if($payload->device_service == 0)
    {
    echo "in withdraw with out rd \n";
    $transaction = AepsTransaction::find($payload->transaction_id);
    if ($transaction->status == 3 || $transaction->status == 4) return; // Do not send same transaction twice.
    if (Carbon::parse($transaction->updated_at)->diffInSeconds(Carbon::now('Asia/Kolkata')) > 15) {
      $transaction->status = 4;
      $transaction->result = 0;
      $transaction->remarks = 'Transaction Expired';
      $transaction->save();
      return;
    }
    $transaction->status = 1;

    date_default_timezone_set('Asia/Kolkata');
    $elements = [
      2 => '19'.$payload->bank_iin.'0'.$payload->aadhar_no,
      3 => '010000',
      4 =>  sprintf('%010u', $payload->amount).'00',
      7 => date('mdHis'),
      11 => $payload->stan,
      12 => date('His'),
      13 => date('md'),
      18 => "6012",
      41 => $payload->device_id,
      49 => '356',
      123 => '015210101213146000',
      127 => $this->fetchDE127($payload->fingerprint)
    ];

    $params = [
    'mti' => '0200',
    'elements' => $elements
    ];

    $isoFactory = new ISO8583();
    $isoMessage = $isoFactory->create($params);

    $paramsLog = $params;
    $paramsLog['elements']['127'] = $this->iso127Log;
    $this->isoMessageLog = $isoFactory->create($paramsLog);

    $transaction->status = 2;
    \Log::info('iso8583Message: '.$this->isoMessageLog);

    $rawResponse = $this->transact($payload, $isoMessage);
    $transaction->status = 3;
    $transaction->save();
    \Log::info(json_encode($rawResponse));

    $vendor = Vendor::where('csr_id', $payload->csr_id)->first();
    $vendor->freshness_factor = $rawResponse->nextFreshnessFactor;
    $vendor->save();

    $transaction->bank_response_code = $rawResponse->responseCode;


    if ($rawResponse->responseCode != '00') {
      $transaction->status = 4;
      $transaction->result = 0;
    }

    if (isset($rawResponse->object)) {
      $isoParsed = $isoFactory->parse($rawResponse->object->iso8583Message);
      $transaction->status = 4;
      $transaction->result_code = $isoParsed[39];
      $transaction->result = $transaction->result_code == '00' ? 1 : 0;
      $transaction->balance = isset($isoParsed[54]) ? $this->fetchRblAmount($isoParsed[54]) : 0;
      $transaction->rrn = isset($isoParsed[37]) ? $isoParsed[37] : ''; // @TODO: Change to null in db and here
      $transaction->uidai_auth_code = isset($isoParsed[48]) ? $isoParsed[48] : '';
      echo $transaction->result;
    }

    if ($transaction->result == 1) {
      $vendor = Vendor::where('csr_id', $payload->csr_id)->lockForUpdate()->first();
      if($vendor->master_wallet_id == 1) 
       {
        $distributordetails = vendor::where('user_id',$vendor->parent_id)->first();
        $distributordetails->balance = $this->updateWalletmuthoot($vendor, $transaction);
        $distributordetails->save();

       }else 
       {
          $vendor->balance = $this->updateWallet($vendor, $transaction);
          $vendor->save();

       } 
		
		if(isset($payload->remark1) && isset($payload->remark2))
    {

		  $muthootResponse=$this->muthootDataSend($payload);

		}

    }

    $transaction->save();
  }
  }

  private function fetchDE127 ($fingerprint)
  {
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];
    $data = ['isoTemplate' => $fingerprint];
    $body = Unirest\Request\Body::json($data);

    $response = Unirest\Request::post('http://localhost:3000/auth', $headers, $body);

    if ($response->code >= 400) {
      Log::info($response->code.' '.json_encode($response->body));
      return Response::json(['message' => 'Service failure. Please check back in a while.'], 500);
    }

    $bitmap127 = '00'.''.(strlen($response->body)+25).'00000400800000000'.''.strlen($response->body);

    $ext_transaction_type = '9117';

    $this->iso127Log = $bitmap127.'XXXXXX'.$ext_transaction_type;

    return $bitmap127.$response->body.$ext_transaction_type;
  }

  private function transact ($payload, $iso)
  {


    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];
    $data = [
      'terminalId' => $payload->terminal_id,
      'freshnessFactor' => $payload->freshness_factor,
      'transType' => '133',
      'csrId' => $payload->csr_id,
      'requestId' => $payload->transaction_id,
      'resentCount' => '1',
      'deviceId' => $payload->device_id,
      'txnTime' => date("M j, Y G:i:s A"),
	  'channel' =>'RDI1849363',
      'object' => [
        'isVoidTxn' => 'false',
        'iso8583Message' => $iso
      ],
      'version' => '1.2.7.1'
    ];
    $body = Unirest\Request\Body::json($data);
    $bodyLog = Unirest\Request\Body::json($this->formatRequest($data));
    $aeps_transaction_log = AepsTransactionLog::create(['transaction_id' => $payload->transaction_id, 'request' => json_encode($bodyLog)]);
    \Log::info('Request: '.json_encode($bodyLog));

     

    $response = Unirest\Request::post(getenv('RBL_URL'), $headers, $body);

    $tx = AepsTransaction::where('id', $payload->transaction_id)->first();
    $tx->aadhar_no = substr_replace($tx->aadhar_no, '********', 0, 8);
    $tx->save();



    $aeps_transaction_log->response = json_encode($response->body);
    $aeps_transaction_log->save();

    if ($response->code >= 400) {
      Log::info($response->code.' '.json_encode($response->body));
      // return Response::json(['message' => 'Service failure. Please check back in a while.'], 500);
    }

    return $response->body;
  }

  private function fetchRblAmount ($amount)
  {
    return ltrim(substr($amount, 8, 10), '0').".".substr($amount, 18, 2);
  }
    private function updateWalletcredit($vendor, $transaction)
    {
    // $vendor->balance += $transaction->type == 1 ?  -$transaction->amount : ($transaction->type == 2 ? $transaction->amount : 0);
      $amount_wallet_transaction = WalletTransaction::create([
        'user_id' => $vendor->user_id,
        'transaction_type' => 1,
        'activity' =>  'Refund',
        'narration'=>'TransID='.$transaction->id,
        'amount' => $transaction->amount,
        'balance' => $vendor->balance
      ]);

      $key = $transaction->type == 1 ? 'debit_id' : ($transaction->type == 2 ? 'credit_id' : '');

      AepsWalletAction::create([
        'user_id' => $vendor->user_id,
        'amount' => $transaction->amount,
        'status' => 1,
        $key => $amount_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => false
      ]);



    }

  private function updateWallet ($vendor, $transaction) 
  {
    // $master = CommissionMaster::where('user_id', $vendor->user_id)->where('min' <= $transaction->amount)->where('max' >= $transaction->amount)->first();
    $commission = $this->calculateCommission($transaction->type, $vendor->user_id, $transaction->amount);
    $distCommission = $this->calculateCommission($transaction->type, $vendor->parent_id, $transaction->amount);
    $superDistId = Vendor::where('user_id', $vendor->parent_id)->first()->parent_id;
    $superDistCommission = $this->calculateCommission($transaction->type, $superDistId, $transaction->amount);

   

    // Perform Wallet Updations for Amount Transacted
    if ($transaction->type == 1 || $transaction->type == 2) {
      $vendor->balance += $transaction->type == 1 ?  -$transaction->amount : ($transaction->type == 2 ? $transaction->amount : 0);
      $amount_wallet_transaction = WalletTransaction::create([
        'user_id' => $vendor->user_id,
        'transaction_type' => $transaction->type == 1 ? 0 : ($transaction->type == 2 ? 1 : null),
        'activity' => $transaction->type == 1 ? 'debit' :($transaction->type == 2 ? 'credit' : ' '),
        'narration'=>'TransID='.$transaction->id,
        'amount' => $transaction->amount,
        'balance' => $vendor->balance
      ]);

      $key = $transaction->type == 1 ? 'debit_id' : ($transaction->type == 2 ? 'credit_id' : '');

      AepsWalletAction::create([
        'user_id' => $vendor->user_id,
        'amount' => $transaction->amount,
        'status' => 1,
        $key => $amount_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => false
      ]);

      $dipl_vendor = Vendor::where('type', 8)->lockForUpdate()->first();
      $dipl_vendor->balance += $transaction->type == 1 ?  -$transaction->amount : ($transaction->type == 2 ? $transaction->amount : 0);
      $dipl_vendor->save();
      $dipl_amount_wallet_transaction = WalletTransaction::create([
        'user_id' => $dipl_vendor->user_id,
        'transaction_type' => $transaction->type == 1 ? 0 : ($transaction->type == 2 ? 1 : null),
        'activity' =>  $transaction->type == 1 ? 'debit' :($transaction->type == 2 ? 'credit' : ' '),
         'narration'=>'TransID='.$transaction->id,
        'amount' => $transaction->amount,
        'balance' => $dipl_vendor->balance
      ]);

      AepsWalletAction::create([
        'user_id' => $dipl_vendor->user_id,
        'amount' => $transaction->amount,
        'status' => 1,
        $key => $dipl_amount_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => false
      ]);

    }

    $dipl_vendor = Vendor::where('type', 8)->lockForUpdate()->first();

    // Perform Wallet Updations for Commission
    if ($commission && $dipl_vendor->commission && $vendor->commission) {
      $commissionAmount = 0;
      if ($commission['rate_type'] == 1)
        $commissionAmount = $commission['rate'];
      if ($commission['rate_type'] == 2)
          $commissionAmount = $transaction->amount * $commission['rate']/100;
      $vendor->balance += $commissionAmount;  
      $commission_wallet_transaction = WalletTransaction::create([
        'user_id' => $vendor->user_id,
        'transaction_type' => 1,
      'activity' => 'credit-commission',
     'narration'=>'TransID='.$transaction->id,
        'amount' => $commissionAmount,
        'balance' => $vendor->balance
      ]);

      AepsWalletAction::create([
        'user_id' => $vendor->user_id,
        'amount' => $commissionAmount,
        'status' => 1,
        'credit_id' => $commission_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => true
      ]);
    }

    if ($distCommission && $dipl_vendor->commission) {
      $distributor = Vendor::where('user_id', $vendor->parent_id)->lockForUpdate()->first();
      $distCommissionAmount = 0;
      if ($distributor && $distributor->commission == 1) {
        if ($distCommission['rate_type'] == 1)
          $distCommissionAmount = $distCommission['rate'];
        if ($distCommission['rate_type'] == 2)
          $distCommissionAmount = $transaction->amount * $distCommission['rate']/100;
        $distributor->balance += $distCommissionAmount;
        $commission_wallet_transaction = WalletTransaction::create([
          'user_id' => $distributor->user_id,
          'transaction_type' => 1,
          'activity' => 'credit-commission',
          'narration'=>'TransID='.$transaction->id,
          'amount' => $distCommissionAmount,
          'balance' => $distributor->balance
        ]);

        $distributor->save();

        AepsWalletAction::create([
          'user_id' => $distributor->user_id,
          'amount' => $distCommissionAmount,
          'status' => 1,
          'credit_id' => $commission_wallet_transaction->id,
          'transaction_id' => $transaction->id,
          'transaction_type' => $transaction->type,
          'commission' => true
        ]);
      } 
    }

    if ($superDistCommission && $dipl_vendor->commission) {
      $superDistributor = Vendor::where('user_id', $superDistId)->lockForUpdate()->first();
      $superDistCommissionAmount = 0;
      if ($superDistributor && $superDistributor->commission == 1) {
        if ($superDistCommission['rate_type'] == 1)
          $superDistCommissionAmount = $superDistCommission['rate'];
        if ($superDistCommission['rate_type'] == 2)
          $superDistCommissionAmount = $transaction->amount * $superDistCommission['rate']/100;
        $superDistributor->balance += $superDistCommissionAmount;
        $commission_wallet_transaction = WalletTransaction::create([
          'user_id' => $superDistributor->user_id,
          'transaction_type' => 1,
          'activity' =>  'credit-commission',
         'narration'=>'TransID='.$transaction->id,
          'amount' => $superDistCommissionAmount,
          'balance' => $superDistributor->balance
        ]);

        $superDistributor->save();

        AepsWalletAction::create([
          'user_id' => $superDistributor->user_id,
          'amount' => $superDistCommissionAmount,
          'status' => 1,
          'credit_id' => $commission_wallet_transaction->id,
          'transaction_id' => $transaction->id,
          'transaction_type' => $transaction->type,
          'commission' => true
        ]);
      }

        //white label code
    if($superDistributor->user_id == 7829 &&  (501 <= $transaction->amount) && ($transaction->amount <= 1999))
       {
   
           //super dist amount debit for white label

           $superDistributors = Vendor::where('user_id', $superDistId)->lockForUpdate()->first();
           $debit_amount=(($transaction->amount * 0.30) / 100);
           $final_debit=6-$debit_amount;
           $superDistributors->balance -= $final_debit;
           
         
            $wallet_transaction = WalletTransaction::create([
          'user_id' => $superDistributor->user_id,
          'transaction_type' => 0,
          'activity' =>  'debit-commission',
          'narration'=>'TransID='.$transaction->id.'- Debited Against Extra Commission.',
          'amount' => $final_debit,
          'balance' => $superDistributors->balance
          ]);

          $superDistributors->save();
      

          AepsWalletAction::create([
            'user_id' => $superDistributor->user_id,
            'amount' => $final_debit,
            'status' => 1,
            'debit_id' => $wallet_transaction->id,
            'transaction_type' => 0,
            'commission' => true
          ]);  
 


         //admin credit amount in wallet for white label

           $dipl_vendor = Vendor::where('type', 8)->lockForUpdate()->first();
           $dipl_vendor->balance +=$final_debit;
           


          $wallet_transaction = WalletTransaction::create([
          'user_id' => $dipl_vendor->user_id,
          'transaction_type' => 1,
          'activity' =>  'credit-commission',
          'narration'=>'TransID='.$transaction->id.'- Credited Against Extra Commission.',
          'amount' => $final_debit,
          'balance' => $dipl_vendor->balance
          ]);

          $dipl_vendor->save();
      

          AepsWalletAction::create([
            'user_id' => $dipl_vendor->user_id,
            'amount' => $final_debit,
            'status' => 1,
            'credit_id' => $wallet_transaction->id,
            'transaction_type' => 1,
            'commission' => true
          ]);  


       }
      
    }

    return $vendor->balance;
  }

  private function updateWalletmuthoot ($vendor, $transaction) 
  {
    // $master = CommissionMaster::where('user_id', $vendor->user_id)->where('min' <= $transaction->amount)->where('max' >= $transaction->amount)->first();
    $commission = $this->calculateCommission($transaction->type, $vendor->user_id, $transaction->amount);
    $distCommission = $this->calculateCommission($transaction->type, $vendor->parent_id, $transaction->amount);
    $superDistId = Vendor::where('user_id', $vendor->parent_id)->first()->parent_id;
    $superDistCommission = $this->calculateCommission($transaction->type, $superDistId, $transaction->amount);
   $distributordetails=Vendor::where('user_id',$vendor->parent_id)->first();
// echo "distributor commission-".$distCommission;
    // Perform Wallet Updations for Amount Transacted
    if ($transaction->type == 1 || $transaction->type == 2) {
      $distributordetails=Vendor::where('user_id',$vendor->parent_id)->first();

      //$vendor->balance;
      $distributordetails->balance += $transaction->type == 1 ?  -$transaction->amount : ($transaction->type == 2 ? $transaction->amount : 0);
     // $distributordetails->save();

      $amount_wallet_transaction = WalletTransaction::create([
        'user_id' => $distributordetails->user_id,
        'transaction_type' => $transaction->type == 1 ? 0 : ($transaction->type == 2 ? 1 : null),
         'activity' => $transaction->type == 1 ? 'debit' :($transaction->type == 2 ? 'credit' : ' '),
        'narration'=>'TransID='.$transaction->id,
        'amount' => $transaction->amount,
        'balance' => $distributordetails->balance
      ]);

      $key = $transaction->type == 1 ? 'debit_id' : ($transaction->type == 2 ? 'credit_id' : '');

      AepsWalletAction::create([
        'user_id' => $distributordetails->user_id,
        'amount' => $transaction->amount,
        'status' => 1,
        $key => $amount_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => false
      ]);

      $dipl_vendor = Vendor::where('type', 8)->lockForUpdate()->first();
      $dipl_vendor->balance += $transaction->type == 1 ?  -$transaction->amount : ($transaction->type == 2 ? $transaction->amount : 0);
      $dipl_vendor->save();
      $dipl_amount_wallet_transaction = WalletTransaction::create([
        'user_id' => $dipl_vendor->user_id,
        'transaction_type' => $transaction->type == 1 ? 0 : ($transaction->type == 2 ? 1 : null),
         'activity' =>  $transaction->type == 1 ? 'debit' :($transaction->type == 2 ? 'credit' : ' '),
         'narration'=>'TransID='.$transaction->id,
        'amount' => $transaction->amount,
        'balance' => $dipl_vendor->balance
      ]);

      AepsWalletAction::create([
        'user_id' => $dipl_vendor->user_id,
        'amount' => $transaction->amount,
        'status' => 1,
        $key => $dipl_amount_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => false
      ]);

    }

    $dipl_vendor = Vendor::where('type', 8)->lockForUpdate()->first();

    // Perform Wallet Updations for Commission
    if ($commission && $dipl_vendor->commission && $vendor->commission) {
      $commissionAmount = 0;
      if ($commission['rate_type'] == 1)
        $commissionAmount = $commission['rate'];
      if ($commission['rate_type'] == 2)
          $commissionAmount = $transaction->amount * $commission['rate']/100;

      $vendor->balance += $commissionAmount;  
      $vendor->save();
     
      $commission_wallet_transaction = WalletTransaction::create([
        'user_id' => $vendor->user_id,
        'transaction_type' => 1,
        'activity' => 'credit',
     'narration'=>'TransID='.$transaction->id,
        'amount' => $commissionAmount,
        'balance' => $vendor->balance
      ]);


      AepsWalletAction::create([
        'user_id' => $vendor->user_id,
        'amount' => $commissionAmount,
        'status' => 1,
        'credit_id' => $commission_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => true
      ]);
    }

    if ($distCommission && $dipl_vendor->commission) {
      echo 'check...';
      print_r($distCommission);
      
      $distributor = Vendor::where('user_id', $vendor->parent_id)->lockForUpdate()->first();
      $distCommissionAmount = 0;
      if ($distributor && $distributor->commission == 1) {
        if ($distCommission['rate_type'] == 1)
          $distCommissionAmount = $distCommission['rate'];
        if ($distCommission['rate_type'] == 2)
          $distCommissionAmount = $transaction->amount * $distCommission['rate']/100;
        //$distributor->balance += $distCommissionAmount; check ones
        $distributordetailsblance = $distCommissionAmount + $distributordetails->balance;
        $commission_wallet_transaction = WalletTransaction::create([
          'user_id' => $distributor->user_id,
          'transaction_type' => 1,
           'activity' => 'credit',
          'narration'=>'TransID='.$transaction->id,
          'amount' => $distCommissionAmount,
          'balance' => $distributordetailsblance
        ]);

        $distributor->save();



        AepsWalletAction::create([
          'user_id' => $distributor->user_id,
          'amount' => $distCommissionAmount,
          'status' => 1,
          'credit_id' => $commission_wallet_transaction->id,
          'transaction_id' => $transaction->id,
          'transaction_type' => $transaction->type,
          'commission' => true
        ]);
      } 
    }

    if ($superDistCommission && $dipl_vendor->commission) {
      $superDistributor = Vendor::where('user_id', $superDistId)->lockForUpdate()->first();
      $superDistCommissionAmount = 0;
      if ($superDistributor && $superDistributor->commission == 1) {
        if ($superDistCommission['rate_type'] == 1)
          $superDistCommissionAmount = $superDistCommission['rate'];
        if ($superDistCommission['rate_type'] == 2)
          $superDistCommissionAmount = $transaction->amount * $superDistCommission['rate']/100;
        $superDistributor->balance += $superDistCommissionAmount;
        $commission_wallet_transaction = WalletTransaction::create([
          'user_id' => $superDistributor->user_id,
          'transaction_type' => 1,
          'activity' =>  'credit',
         'narration'=>'TransID='.$transaction->id,
          'amount' => $superDistCommissionAmount,
          'balance' => $superDistributor->balance
        ]);

        $superDistributor->save();

        AepsWalletAction::create([
          'user_id' => $superDistributor->user_id,
          'amount' => $superDistCommissionAmount,
          'status' => 1,
          'credit_id' => $commission_wallet_transaction->id,
          'transaction_id' => $transaction->id,
          'transaction_type' => $transaction->type,
          'commission' => true
        ]);
      }
      
    }
    //dd($distributordetails->balance);
    return $distributordetails->balance;
  }


  private function calculateCommission ($type, $userId, $amount)
  {
  	$rate = $this->calculateCommissionRate($type, $amount, $userId);
  	if (! $rate) return false;
  	if ($type == 0) {
  		return ['rate' => $rate->balance_enquiry_rate, 'rate_type' => 1];
  	}
  	return ['rate' => $rate->rate, 'rate_type' => $rate->rate_type];
  }

  private function calculateCommissionRate ($type, $amount, $userId)
  {
    if ($type == 0) {
      $rate = CommissionRate::where('user_id', $userId)->first();
      if (! $rate) return false;
      return $rate;
  	}
    $user_data=Vendor::where('user_id',$userId)->first();

    $master = CommissionMaster::where('min', '<=', $amount)->where('max', '>=', $amount)->where('commission_master_type',$user_data->commission_master_type)->first();
    
  	if (! $master) return false;
  	$rate = CommissionRate::where('user_id', $userId)->where('master_id', $master->id)->first();
  	if (! $rate) return false;
  	return $rate;
  }

  private function formatRequest ($request)
  {
    $dataLog = $request;
    $dataLog['object']['iso8583Message'] = $this->isoMessageLog;
    return $dataLog;
  }

  private function updateWalletforcredit($vendor, $transaction) 
 {
    // $master = CommissionMaster::where('user_id', $vendor->user_id)->where('min' <= $transaction->amount)->where('max' >= $transaction->amount)->first();
    $commission = $this->calculateCommission($transaction->type, $vendor->user_id, $transaction->amount);
    $distCommission = $this->calculateCommission($transaction->type, $vendor->parent_id, $transaction->amount);
    $superDistId = Vendor::where('user_id', $vendor->parent_id)->first()->parent_id;
    $superDistCommission = $this->calculateCommission($transaction->type, $superDistId, $transaction->amount);


    // Perform Wallet Updations for Amount Transacted
    if ($transaction->type == 1 || $transaction->type == 2) {
      //$vendor->balance += $transaction->type == 1 ?  -$transaction->amount : ($transaction->type == 2 ? $transaction->amount : 0);
      /*$amount_wallet_transaction = WalletTransaction::create([
        'user_id' => $vendor->user_id,
        'transaction_type' => $transaction->type == 1 ? 0 : ($transaction->type == 2 ? 1 : null),
        'activity' =>  $transaction->type == 1 ? 'debitt' :($transaction->type == 2 ? 'creditt' : ' '),
        'narration'=>'TransID='.$transaction->id,
        'amount' => $transaction->amount,
        'balance' => $vendor->balance
      ]);

      $key = $transaction->type == 1 ? 'debit_id' : ($transaction->type == 2 ? 'credit_id' : '');

      AepsWalletAction::create([
        'user_id' => $vendor->user_id,
        'amount' => $transaction->amount,
        'status' => 1,
        $key => $amount_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => false
      ]);
    */
     $key = $transaction->type == 1 ? 'debit_id' : ($transaction->type == 2 ? 'credit_id' : '');

      $dipl_vendor = Vendor::where('type', 8)->lockForUpdate()->first();
      $dipl_vendor->balance += $transaction->type == 1 ?  -$transaction->amount : ($transaction->type == 2 ? $transaction->amount : 0);
      $dipl_vendor->save();
      $dipl_amount_wallet_transaction = WalletTransaction::create([
        'user_id' => $dipl_vendor->user_id,
        'transaction_type' => $transaction->type == 1 ? 0 : ($transaction->type == 2 ? 1 : null),
        'activity' =>  $transaction->type == 1 ? 'debit' :($transaction->type == 2 ? 'credit' : ' '),
        'narration'=>'TransID='.$transaction->id,
        'amount' => $transaction->amount,
        'balance' => $dipl_vendor->balance
      ]);

      AepsWalletAction::create([
        'user_id' => $dipl_vendor->user_id,
        'amount' => $transaction->amount,
        'status' => 1,
        $key => $dipl_amount_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => false
      ]);

    }

    $dipl_vendor = Vendor::where('type', 8)->lockForUpdate()->first();

    // Perform Wallet Updations for Commission
    if ($commission && $dipl_vendor->commission && $vendor->commission) {
      $commissionAmount = 0;
      if ($commission['rate_type'] == 1)
        $commissionAmount = $commission['rate'];
      if ($commission['rate_type'] == 2)
          $commissionAmount = $transaction->amount * $commission['rate']/100;
      $vendor->balance += $commissionAmount;  
      $commission_wallet_transaction = WalletTransaction::create([
        'user_id' => $vendor->user_id,
        'transaction_type' => 1,
        'activity' => 'credit',
        'narration'=>'TransID='.$transaction->id,
        'amount' => $commissionAmount,
        'balance' => $vendor->balance
      ]);

      AepsWalletAction::create([
        'user_id' => $vendor->user_id,
        'amount' => $commissionAmount,
        'status' => 1,
        'credit_id' => $commission_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => true
      ]);
    }

    if ($distCommission && $dipl_vendor->commission) {
      $distributor = Vendor::where('user_id', $vendor->parent_id)->lockForUpdate()->first();
      $distCommissionAmount = 0;
      if ($distributor && $distributor->commission == 1) {
        if ($distCommission['rate_type'] == 1)
          $distCommissionAmount = $distCommission['rate'];
        if ($distCommission['rate_type'] == 2)
          $distCommissionAmount = $transaction->amount * $distCommission['rate']/100;
        $distributor->balance += $distCommissionAmount;
        $commission_wallet_transaction = WalletTransaction::create([
          'user_id' => $distributor->user_id,
          'transaction_type' => 1,
          'activity' => 'credit',
        'narration'=>'TransID='.$transaction->id,
          'amount' => $distCommissionAmount,
          'balance' => $distributor->balance
        ]);

        $distributor->save();

        AepsWalletAction::create([
          'user_id' => $distributor->user_id,
          'amount' => $distCommissionAmount,
          'status' => 1,
          'credit_id' => $commission_wallet_transaction->id,
          'transaction_id' => $transaction->id,
          'transaction_type' => $transaction->type,
          'commission' => true
        ]);
      } 
    }

    if ($superDistCommission && $dipl_vendor->commission) {
      $superDistributor = Vendor::where('user_id', $superDistId)->lockForUpdate()->first();
      $superDistCommissionAmount = 0;
      if ($superDistributor && $superDistributor->commission == 1) {
        if ($superDistCommission['rate_type'] == 1)
          $superDistCommissionAmount = $superDistCommission['rate'];
        if ($superDistCommission['rate_type'] == 2)
          $superDistCommissionAmount = $transaction->amount * $superDistCommission['rate']/100;
        $superDistributor->balance += $superDistCommissionAmount;
        $commission_wallet_transaction = WalletTransaction::create([
          'user_id' => $superDistributor->user_id,
          'transaction_type' => 1,
          'activity' =>  'credit',
        'narration'=>'TransID='.$transaction->id,
          'amount' => $superDistCommissionAmount,
          'balance' => $superDistributor->balance
        ]);

        $superDistributor->save();

        AepsWalletAction::create([
          'user_id' => $superDistributor->user_id,
          'amount' => $superDistCommissionAmount,
          'status' => 1,
          'credit_id' => $commission_wallet_transaction->id,
          'transaction_id' => $transaction->id,
          'transaction_type' => $transaction->type,
          'commission' => true
        ]);
      }
      
    }

    return $vendor->balance;
  }


//muthoot deposit function

  private function updateWalletforcreditmuthoot($vendor, $transaction) 
 {
    // $master = CommissionMaster::where('user_id', $vendor->user_id)->where('min' <= $transaction->amount)->where('max' >= $transaction->amount)->first();
    $commission = $this->calculateCommission($transaction->type, $vendor->user_id, $transaction->amount);
    $distCommission = $this->calculateCommission($transaction->type, $vendor->parent_id, $transaction->amount);
    $superDistId = Vendor::where('user_id', $vendor->parent_id)->first()->parent_id;
    $superDistCommission = $this->calculateCommission($transaction->type, $superDistId, $transaction->amount);


    // Perform Wallet Updations for Amount Transacted
    if ($transaction->type == 1 || $transaction->type == 2) {
      //$vendor->balance += $transaction->type == 1 ?  -$transaction->amount : ($transaction->type == 2 ? $transaction->amount : 0);
      /*$amount_wallet_transaction = WalletTransaction::create([
        'user_id' => $vendor->user_id,
        'transaction_type' => $transaction->type == 1 ? 0 : ($transaction->type == 2 ? 1 : null),
        'activity' =>  $transaction->type == 1 ? 'debitt' :($transaction->type == 2 ? 'creditt' : ' '),
        'narration'=>'TransID='.$transaction->id,
        'amount' => $transaction->amount,
        'balance' => $vendor->balance
      ]);

      $key = $transaction->type == 1 ? 'debit_id' : ($transaction->type == 2 ? 'credit_id' : '');

      AepsWalletAction::create([
        'user_id' => $vendor->user_id,
        'amount' => $transaction->amount,
        'status' => 1,
        $key => $amount_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => false
      ]);
    */
     $key = $transaction->type == 1 ? 'debit_id' : ($transaction->type == 2 ? 'credit_id' : '');

      $dipl_vendor = Vendor::where('type', 8)->lockForUpdate()->first();
      $dipl_vendor->balance += $transaction->type == 1 ?  -$transaction->amount : ($transaction->type == 2 ? $transaction->amount : 0);
      $dipl_vendor->save();
      $dipl_amount_wallet_transaction = WalletTransaction::create([
        'user_id' => $dipl_vendor->user_id,
        'transaction_type' => $transaction->type == 1 ? 0 : ($transaction->type == 2 ? 1 : null),
        'activity' =>  $transaction->type == 1 ? 'debit' :($transaction->type == 2 ? 'credit' : ' '),
        'narration'=>'TransID='.$transaction->id,
        'amount' => $transaction->amount,
        'balance' => $dipl_vendor->balance
      ]);

      AepsWalletAction::create([
        'user_id' => $dipl_vendor->user_id,
        'amount' => $transaction->amount,
        'status' => 1,
        $key => $dipl_amount_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => false
      ]);

    }

    $dipl_vendor = Vendor::where('type', 8)->lockForUpdate()->first();

    // Perform Wallet Updations for Commission
    if ($commission && $dipl_vendor->commission && $vendor->commission) {
      $commissionAmount = 0;
      if ($commission['rate_type'] == 1)
        $commissionAmount = $commission['rate'];
      if ($commission['rate_type'] == 2)
          $commissionAmount = $transaction->amount * $commission['rate']/100;
      $vendor->balance += $commissionAmount;  
      $commission_wallet_transaction = WalletTransaction::create([
        'user_id' => $vendor->user_id,
        'transaction_type' => 1,
        'activity' => 'credit',
        'narration'=>'TransID='.$transaction->id,
        'amount' => $commissionAmount,
        'balance' => $vendor->balance
      ]);

      AepsWalletAction::create([
        'user_id' => $vendor->user_id,
        'amount' => $commissionAmount,
        'status' => 1,
        'credit_id' => $commission_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => true
      ]);
    }

    if ($distCommission && $dipl_vendor->commission) {
      $distributor = Vendor::where('user_id', $vendor->parent_id)->lockForUpdate()->first();
      $distCommissionAmount = 0;
      if ($distributor && $distributor->commission == 1) {
        if ($distCommission['rate_type'] == 1)
          $distCommissionAmount = $distCommission['rate'];
        if ($distCommission['rate_type'] == 2)
          $distCommissionAmount = $transaction->amount * $distCommission['rate']/100;
        $distributor->balance += $distCommissionAmount;
        $commission_wallet_transaction = WalletTransaction::create([
          'user_id' => $distributor->user_id,
          'transaction_type' => 1,
          'activity' => 'credit',
        'narration'=>'TransID='.$transaction->id,
          'amount' => $distCommissionAmount,
          'balance' => $distributor->balance
        ]);

        $distributor->save();

        AepsWalletAction::create([
          'user_id' => $distributor->user_id,
          'amount' => $distCommissionAmount,
          'status' => 1,
          'credit_id' => $commission_wallet_transaction->id,
          'transaction_id' => $transaction->id,
          'transaction_type' => $transaction->type,
          'commission' => true
        ]);
      } 
    }

    if ($superDistCommission && $dipl_vendor->commission) {
      $superDistributor = Vendor::where('user_id', $superDistId)->lockForUpdate()->first();
      $superDistCommissionAmount = 0;
      if ($superDistributor && $superDistributor->commission == 1) {
        if ($superDistCommission['rate_type'] == 1)
          $superDistCommissionAmount = $superDistCommission['rate'];
        if ($superDistCommission['rate_type'] == 2)
          $superDistCommissionAmount = $transaction->amount * $superDistCommission['rate']/100;
        $superDistributor->balance += $superDistCommissionAmount;
        $commission_wallet_transaction = WalletTransaction::create([
          'user_id' => $superDistributor->user_id,
          'transaction_type' => 1,
          'activity' =>  'credit',
        'narration'=>'TransID='.$transaction->id,
          'amount' => $superDistCommissionAmount,
          'balance' => $superDistributor->balance
        ]);

        $superDistributor->save();

        AepsWalletAction::create([
          'user_id' => $superDistributor->user_id,
          'amount' => $superDistCommissionAmount,
          'status' => 1,
          'credit_id' => $commission_wallet_transaction->id,
          'transaction_id' => $transaction->id,
          'transaction_type' => $transaction->type,
          'commission' => true
        ]);
      }
      
    }

    return $distributor->balance;
  }




  private function muthootDataSend($payload)
  {
    //dd($payload);


    $agentID=$payload->user_id;
    //$agentID='AG45';
    $custName=$payload->remark1;
    $mobileNo=$payload->remark2;
    $transNo=$payload->transaction_id;
    $trnAmt=$payload->amount;
    $trnDatetime=date("Y-m-d")."T".date("h:i:s");//'2017-02-11T00:00:00';//date('Y-m-d h:i:s');
    $trntype=$payload->m_action;
    //$trntype='P';
    error_reporting(0);
      $soap_request  = "<x:Envelope xmlns:x='http://schemas.xmlsoap.org/soap/envelope/' xmlns:tem='http://tempuri.org/' xmlns:bo='http://schemas.datacontract.org/2004/07/BO'>
     <x:Header/>
        <x:Body>
            <tem:Post_AEPSResponse>
                <tem:dataAEPS>
                    <bo:agentID>".$agentID."</bo:agentID>
                    <bo:custName>".$custName."</bo:custName>
                    <bo:mobileNo>".$mobileNo."</bo:mobileNo>
                    <bo:transNo>".$transNo."</bo:transNo>
                    <bo:trnAmt>".$trnAmt."</bo:trnAmt>
                    <bo:trnDatetime>".$trnDatetime."</bo:trnDatetime>
                    <bo:trntype>".$trntype."</bo:trntype>
                </tem:dataAEPS>
            </tem:Post_AEPSResponse>
        </x:Body>
    </x:Envelope>";
     
      $header = array(
        "Content-type: text/xml; charset=utf-8",
        "Accept: text/xml",
        "Cache-Control: no-cache",
        "Pragma: no-cache",
        "SOAPAction: http://tempuri.org/IAEPSResponse/Post_AEPSResponse",
        "Content-length: ".strlen($soap_request)
      );
     
    $url = 'http://aeps.muthootapps.com/AEPSResponse.svc?singleWsdl';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT,        10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,     $soap_request);
    curl_setopt($ch, CURLOPT_HEADER,1); 
    curl_setopt($ch, CURLOPT_HTTPHEADER,     $header);  
    curl_setopt($ch,CURLOPT_HEADER ,FALSE);
    $response = curl_exec($ch);
    //echo '<pre>';
    //print_r($response);
    //$your_xml_response = '<Your XML here>';
    $clean_xml = str_ireplace( ['s:','tem:','do:'], '', $response);

    $xml = simplexml_load_string($clean_xml);

    $json_result=json_encode($xml);
    $responseArray = json_decode($json_result,true);
    $responseMesg=$responseArray['Body']['Post_AEPSResponseResponse']['Post_AEPSResponseResult']['a:RespMessage'];
    $responseStatus=$responseArray['Body']['Post_AEPSResponseResponse']['Post_AEPSResponseResult']['a:Status'];
    // print_r('response message:-'.$responseMesg);
    // echo'<pre>';
    // print_r('response Status:-'.$responseStatus);

     $ThirdPartyLogs = new ThirdPartyLogs();
 $ThirdPartyLogs->transaction_id= $payload->transaction_id;
 $ThirdPartyLogs->request =  $soap_request;
 $ThirdPartyLogs->response = $responseMesg."--".$responseStatus;
 $ThirdPartyLogs->save();

    curl_close($ch);
  }
}

$queue = new Rabbit('aeps_transactions');
$queue->subscribe(new MATM());
