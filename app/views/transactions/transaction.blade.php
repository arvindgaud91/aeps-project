<?php use Acme\Auth\Auth; 
 $user = Auth::user();

?>
@extends('layouts.master')
@section('content')
<div ng-controller="TestCtrl" class="head-weight">
   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default panel-border-color panel-border-color-primary">
            <div class="panel-heading panel-heading-divider">@{{labelDict[transactionType]}} <span class="panel-subtitle">Form</span></div>
            <div class="panel-body">
               <form ng-submit="submit()" style="border-radius: 0px;" class="form-horizontal group-border-dashed"  name="WithdrawFrm" novalidate>
                  <div class="row">
                     <div class="col-md-9">
                        <div class="form-group">
                           <label class="col-sm-3 control-label">Aadhaar Number</label>
                           <div class="col-sm-6">
                              <input type="text" ng-model="transaction.aadhar_no" name="aadhar_no" id="adhar_no" maxlength="12"  pattern="[0-9]{12}" placeholder="Enter Aadhaar No" class="form-control err"  required isaadharno >
                              <p ng-show="WithdrawFrm.$submitted && WithdrawFrm.aadhar_no.$invalid" class="err-mark">Please enter the aadhaar number.</p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-3 control-label">Select Bank</label>
                           <div class="col-sm-6">
                              <div
                                 isteven-multi-select
                                 input-model="banks"
                                 output-model="bank"
                                 button-label="name"
                                 item-label="name"
                                 tick-property="ticked"
                                 selection-mode="single"
                                 id="Bank"
                                 name="bank"
                                 class="multiselect-form-control"
                                 ng-class="{'err-mark': (WithdrawFrm.$submitted && ! customRequiredCheck(bank))}"
                                 >
                              </div>
                              <p ng-show="WithdrawFrm.$submitted && ! customRequiredCheck(bank)" class="err-mark">Please select a bank.</p>
                           </div>
                        </div>
                        <!-- <div class="form-group" ng-show>
                           <label class="col-sm-3 control-label">Select Service</label>
                           <div class="col-sm-6">
                              <input type="text" value="Balance Enquiry" readonly="readonly" class="form-control" name="service" id="show_hide" >
                           </div>
                        </div> -->
                        <div class="slidingDiv" ng-show="transactionType=='withdraw' || transactionType=='deposit'">
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Enter Amount</label>
                              <div class="col-sm-6">
                                 <input type="number" ng-model="transaction.amount" name="amount" ng-min="1" ng-max="10000" class="form-control err" placeholder="Enter Amount" ng-required="transactionType=='withdraw' || transactionType=='deposit'" isnumber>
                                 <p ng-show="WithdrawFrm.$submitted && WithdrawFrm.amount.$invalid" class="err-mark">Please enter an amount less than 10000.</p>
                              </div>
                           </div>
                        </div>
                        <div class="form-group" ng-show="distributorType != 'muthoot'">
                           <label class="col-sm-3 control-label" >Customer Name</label>
                           <div class="col-sm-6">
                              <input type="text" ng-model="transaction.cust_name" name="cust_name" id="cust_name"  placeholder="Enter Customer Name" class="form-control err" ng-required="distributorType!='muthoot'">
                              <p ng-show="WithdrawFrm.$submitted && WithdrawFrm.cust_name.$invalid" class="err-mark">Please Enter the Customer Name.</p>
                           </div>
                        </div>
                        <div class="form-group" ng-show="distributorType != 'muthoot'">
                           <label class="col-sm-3 control-label" >Customer Phone Number</label>
                           <div class="col-sm-6">
                              <input type="text" ng-model="transaction.cust_phone_no" name="cust_phone_no" id="cust_phone_no" placeholder="Enter Customer Phone Number" class="form-control err" ng-required="distributorType!='muthoot'">
                              <p ng-show="WithdrawFrm.$submitted && WithdrawFrm.cust_phone_no.$invalid" class="err-mark">Please enter the Customer Phone Number.</p>
                           </div>
                        </div>

                        <div class="form-group"  ng-show="distributorType=='muthoot'">
                           <label class="col-sm-3 control-label" >Customer Name</label>
                           <div class="col-sm-6">
                              <input type="text" ng-model="transaction.remark1" name="remark1" id="remark1"  placeholder="Enter Customer Name" class="form-control err" ng-required="distributorType=='muthoot'" >
                              <p ng-show="WithdrawFrm.$submitted && WithdrawFrm.remark1.$invalid" class="err-mark">Please enter the Customer Name.</p>
                           </div>
                        </div>
                        <div class="form-group" ng-show="distributorType=='muthoot'">
                           <label class="col-sm-3 control-label" >Customer Phone Number</label>
                           <div class="col-sm-6">
                              <input type="text" ng-model="transaction.remark2" name="remark2" id="remark2" placeholder="Enter Customer Phone Number" class="form-control err" ng-required="distributorType=='muthoot'" >
                              <p ng-show="WithdrawFrm.$submitted && WithdrawFrm.remark2.$invalid" class="err-mark">Please enter the Customer Phone Number.</p>
                           </div>
                        </div>
                        <div class="form-group login-submit">
                           <div class="col-sm-3"></div>
                           <div class="col-sm-6">
                              <button  type="submit" name="submit" class="btn btn-primary form-control" ng-hide="txSubmitted" ng-disabled="disabled">Submit Form</button>
                              <button  type="button" name="wait" class="btn btn-primary form-control" ng-show="txSubmitted">Please wait...</button>
                           </div>
                           <div class="col-sm-3"></div>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="form-group">
                          @if($user->vendor->fingerprint_device_id == 1)
                           <div class="row">
                              <div class="col-sm-6" >
                                 <img id="image" name="fingerImg"  ng-src="data:image/png;base64,@{{fingerImg}}" class="fingerImg" ng-class="{'err-mark': (WithdrawFrm.$submitted && !checkFingerPrint())}">
                                 <p ng-show="WithdrawFrm.$submitted && !checkFingerPrint()" class="err-mark">Please enter your fingerprint.</p>
                              </div>
                           </div>
                           @endif

                           @if($user->vendor->fingerprint_device_id == 2)
                           <div class="row">
                              <div class="col-sm-6" >
                                 <img id="image" name="fingerImg"  ng-src="data:image/png;base64,@{{fingerImg}}" class="fingerImg" >
                                 
                              </div>
                           </div>

                           @endif
                           <br>
                           <div class="row">
                              <button type="button" class="btn btn-primary col-sm-6" ng-click="capture()">Capture</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   @stop
   @section('javascript')
   <script>

   angular.module('DIPApp')
   .controller('TestCtrl', ['$scope', '$http', function ($scope, $http) {
      //window.s = $scope;
      $scope.transactionType = {{ json_encode($transactionType) }}

      $scope.distributorType = {{ json_encode($distributorType) }}
      
      $scope.transaction={'action': $scope.transactionType};
      $scope.capture = capture;
      $scope.customRequiredCheck = customRequiredCheck
      $scope.submit = submit
      $scope.checkFingerPrint=checkFingerPrint
      $scope.txSubmitted = false
      $scope.labelDict = {
         'balance-enquiry': 'Balance Enquiry',
         'withdraw': 'Withdraw',
         'deposit': 'Deposit'
      }
      $scope.banks = {{ json_encode($banks) }}.filter(function (bank) {
        return bank.iin != '607393'
      })

       $scope.disabled= true;
       function discoverAvdm()
    {
            <!-- ddlAVDM.empty(); -->

            var primaryUrl = "http://127.0.0.1:";
            url = "";
       $("#ddlAVDM").empty();
      alert("Please wait while discovering port from 11100 to 11120.\nThis will take some time.");
      
                for (var i = 11100; i <= 11120; i++)
                {
        
                    $("#lblStatus").text("Discovering RD service on port : " + i.toString());
                  
            var verb = "RDSERVICE";
                        var err = "";
            
            var res;
            $.support.cors = true;
            var httpStaus = false;
            var jsonstr="";
             var data = new Object();
             var obj = new Object();
            
              $.ajax({
      
              type: "RDSERVICE",
              async: false,
              crossDomain: true,
              url: primaryUrl + i.toString(),
              contentType: "text/xml; charset=utf-8",
              processData: false,
              cache: false,
              async:false,
              crossDomain:true,
              
              success: function (data) {
              
                httpStaus = true;
                res = { httpStaus: httpStaus, data: data };
                  //alert(data);    
                 finalUrl = primaryUrl + i.toString();
                var $doc = $.parseXML(data);
                var CmbData1 =  $($doc).find('RDService').attr('status');
                var CmbData2 =  $($doc).find('RDService').attr('info');
              
                $("#ddlAVDM").append('<option value='+i.toString()+'>(' + CmbData1 +')'+CmbData2+'</option>')
                
              },
              error: function (jqXHR, ajaxOptions, thrownError) {
              //alert(thrownError);
                res = { httpStaus: httpStaus, err: getHttpError(jqXHR) };
              },
            });
            //$("#ddlAVDM").val("0");
            
                }
        $("select#ddlAVDM").prop('selectedIndex', 0);
        
        //$('#txtDeviceInfo').val(DataXML);
         
        var PortVal= $('#ddlAVDM').val($('#ddlAVDM').find('option').first().val()).val();
        
        if(PortVal>11099)
        {
           discoverAvdmFirstNode(PortVal);
        }
        return res; 
    }



  function trim(str) 
  {
      return str.toString().replace(/\r\n/g, "\n");
  }


function base64toHEX(base64)
{

  var raw = atob(base64);

  var HEX = '';

  for ( i = 0; i < raw.length; i++ ) {

    var _hex = raw.charCodeAt(i).toString(16)

    HEX += (_hex.length==2?_hex:'0'+_hex);

  }
  return HEX.toUpperCase();

}

function capture ()
{
$scope.disabled= false;
  //device service is with out rd 
        if($scope.activeUserProfile.vendor.device_service == 0)
        {
        if ($scope.activeUserProfile.vendor.fingerprint_device_id == 0 || $scope.activeUserProfile.vendor.fingerprint_device_id == 1) {
          $http.get('https://localhost:15005/getDeviceDetails')
          .then(function (data) {
            if ($scope.activeUserProfile.vendor.device_sr_no != data.data.DeviceSerial.split('-')[1]) return sweetAlert('Error', 'Please use the registered fingerprint device', 'error')
            $http.get('https://localhost:15005/CaptureFingerprint?10$1')
            .then(data => {
              $scope.fingerImg = data.data.Base64BMPIMage;
            
              $scope.transaction.fingerprint = data.data.Base64ISOTemplate

            })
          })
        }




         if ($scope.activeUserProfile.vendor.fingerprint_device_id == 2) {
          $http.get('https://localhost:8003/mfs100/info')
          .then(function (data) {
            if ($scope.activeUserProfile.vendor.device_sr_no != data.data.DeviceInfo.SerialNo) return sweetAlert('Error', 'Please use the registered fingerprint device', 'error')
            $http.post('https://localhost:8003/mfs100/capture', {'Quality': 60, 'TimeOut': 10})
            .then(data => {
              $scope.fingerImg = data.data.BitmapData;
             
              $scope.transaction.fingerprint = data.data.IsoTemplate
            }, fail)
          }, fail)
        }
 
       }

//device service is rd 
      if($scope.activeUserProfile.vendor.device_service == 1)
      {

        if($scope.activeUserProfile.vendor.fingerprint_device_id == 1)
        {

         return sweetAlert('Error', 'Something went wrong', 'error')
        }

         if ($scope.activeUserProfile.vendor.fingerprint_device_id == 2) {
                      var XML='<?xml version="1.0"?> <PidOptions ver="1.0"> <Opts fCount="1" fType="0" iCount="0" pCount="0" format="0" pidVer="2.0" timeout="10000"  wadh=""  posh="UNKNOWN" env="P" />  </PidOptions>';
              var verb = "CAPTURE";

              var err = "";
              var res;
              $.support.cors = true;
              var httpStaus = false;
              var jsonstr="";


            $.ajax({
      
              type: "CAPTURE",
              async: false,
              crossDomain: true,
              url: "https://127.0.0.1:8005/rd/capture",
              data:XML,
              contentType: "text/xml; charset=utf-8",
              processData: false,
              success: function (data) {
               
           
                var x2js = new X2JS();
                var xmldata = x2js.xml_str2json(data);
                 $scope.fingerImg = xmldata.PidData.Data;
                   
                if ($scope.activeUserProfile.vendor.device_sr_no != xmldata.PidData.DeviceInfo.additional_info.Param[0]._value) return sweetAlert('Error', 'Please use the registered fingerprint device', 'error')


  var Base64 = {


    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",


    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
        },


   
            _utf8_encode: function(string) {
                string = trim(string);
                var utftext = "";

                for (var n = 0; n < string.length; n++) {

                    var c = string.charCodeAt(n);

                    if (c < 128) {
                        utftext += String.fromCharCode(c);
                    }
                    else if ((c > 127) && (c < 2048)) {
                        utftext += String.fromCharCode((c >> 6) | 192);
                        utftext += String.fromCharCode((c & 63) | 128);
                    }
                    else {
                        utftext += String.fromCharCode((c >> 12) | 224);
                        utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                        utftext += String.fromCharCode((c & 63) | 128);
                    }

                }

                return utftext;
            }

  
             }



                 var heaxaskey=base64toHEX(xmldata.PidData.Skey);

                 var heaxahmac=base64toHEX(xmldata.PidData.Hmac);

                 var heaxaData=base64toHEX(xmldata.PidData.Data);

                $scope.transaction.mc = xmldata.PidData.DeviceInfo._mc
                $scope.transaction.udc = xmldata.PidData.DeviceInfo._dc
                $scope.transaction.dpId = xmldata.PidData.DeviceInfo._dpId
                $scope.transaction.rdsId = xmldata.PidData.DeviceInfo._rdsId
                $scope.transaction.rdsVer = xmldata.PidData.DeviceInfo._rdsVer
                $scope.transaction.dc = xmldata.PidData.DeviceInfo._dc
                $scope.transaction.mi = xmldata.PidData.DeviceInfo._mi
                $scope.transaction.pid = '303031323536'+heaxaskey+'3030323030383230313931323330'+'303033303438'+heaxahmac+'30303430313030303030343730303030'+'30303530313030303030343730303030'+'3030363035354d456665783171303363466a594d626633543568425538546e4b726f6c4659484f3764325971595147557654744f426849347051535849'+heaxaData

                
                httpStaus = true;
                res = { httpStaus: httpStaus, data: data };
 
              },
              error: function (jqXHR, ajaxOptions, thrownError) {
              
              alert(thrownError);
                res = { httpStaus: httpStaus, err: getHttpError(jqXHR) };
              },
            });
        }


      }

       
}

      
      function customRequiredCheck (model, key) {
         if (! key)
         return (! model || model.length == 0) ? false : true
         return (! _.has(model, key) || model[key] == "") ? false : true
      }
      function checkFingerPrint () {
         return (! $scope.transaction.fingerprint || $scope.transaction.fingerprint == '') ?  false : true
         //return true;
      }

     
      function submit () 
      {$scope.txSubmitted = true
        if ($scope.activeUserProfile.vendor.fingerprint_device_id == 0 || $scope.activeUserProfile.vendor.fingerprint_device_id == 1) 
          {
         if ($scope.WithdrawFrm.$invalid || ! checkFingerPrint()) return
         
         var transaction = Object.assign($scope.transaction, {'bank_id': $scope.bank[0].id, 'bank_iin': $scope.bank[0].iin});
        
         $http.post('/api/v1/transactions/'+$scope.transactionType, transaction)
          .then(function (data) {
           
            oldToastr.success('Successfully requested '+$scope.labelDict[$scope.transactionType])
            $scope.txSubmitted = true
            $scope.transaction_id = data.data.transaction_id
            poll()
          }, fail)
        }

    if($scope.activeUserProfile.vendor.fingerprint_device_id == 2)
    {
      if ($scope.WithdrawFrm.$invalid) return
         
         var transaction = Object.assign($scope.transaction, {'bank_id': $scope.bank[0].id, 'bank_iin': $scope.bank[0].iin});
       
         $http.post('/api/v1/transactions/'+$scope.transactionType, transaction)
          .then(function (data) {
            oldToastr.success('Successfully requested '+$scope.labelDict[$scope.transactionType])
            $scope.txSubmitted = true
            $scope.transaction_id = data.data.transaction_id
            poll()
          }, fail)

    }

  



      }


 


 
      //@TODO: Implement polling using observables

      function poll () {
        $http.get('/api/v1/transactions/'+$scope.transaction_id+'/actions/status')
        .then(function (data) {
          handle(data)
        })
      }

      function handle (data) {
        if (data.data.status == "success" || data.data.status == "fail") {
          location.href = '/transactions/'+$scope.transaction_id+'/actions/receipt'
          return
        }
        poll()
      }

      function fail (err) {
        
         if (err.status == 422) {
           if (err.data.code == 0 || err.data.code == 1)
            return sweetAlert('Error', err.data.message, 'error')
          var msg = _.map(err.data, e => {
            return e[0]
          }).join(" ")
          return sweetAlert('Error', msg, 'error')
         }
         sweetAlert('Error', 'Something went wrong', 'error')
      }
   }])

    
   </script>
    
    <script>
        $(document).ready(function(){
            // Loading Button Theme API Call
            var l = $( '.ladda-button-demo' ).ladda();
              l.click(function(){

                  // Start loading
                  l.ladda( 'start' );

                  // Do something in backend and then stop ladda
                  alert('Its done.');
                  // setTimeout() is only for demo purpose
                  setTimeout(function(){
                      l.ladda('stop');
                  },2000)

              });
        })
    </script>

   @stop
   