<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="ProfileCtrl" class="head-weight">
   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default">
            <div class="panel-heading">User profile</div>
            <div class="tab-container ">
               <!--  -->
               <div class="tab-content ">
                  <div id="profile">
                     <div class="panel-default panel-default panel-border-color panel-border-color-primary m-b-0">
                        <div class="panel-body">
                           <div class="panel-heading text-center"><strong style="    font-size: 18px;">Basic Details</strong></div>
                           <form name="VendorProfileFrm" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed" ng-submit="saveProfile(user)" novalidate>

                              
                                                            
                              <div id="advance_details">
                                  
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">USER ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="userid" value="@{{user['id']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group" ng-hide="vendorType==3">
                                                <label class="col-sm-3 control-label">@{{typeDic[vendorType]}}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="distributor" value="@{{user.vendor.parent['name']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">NAME</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="name" value="@{{user['name']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">EMAIL</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="email" value="@{{user['email']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">MOBILE NO</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="mobile_no" value="@{{user['phone_no']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">PAN NO</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="mobile_no" value="@{{user.vendor['pan_no']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">CITY</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="city" value="@{{user.vendor.cityName}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">STATE</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="state" value="@{{user.vendor.stateName}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">ZONE</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="zone" value="@{{user.vendor.zoneName}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div ng-show="vendorType==1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel-heading text-center"><strong style="font-size: 18px;">Advance Details</strong>
</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">CSR ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="csr_id" value="@{{user.vendor['csr_id']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">TERMINAL ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="terminal_id" value="@{{user.vendor['terminal_id']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">DEVICE ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="device_id" value="@{{user.vendor['device_id']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">DEVICE SERIAL NO.</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="device_serial_no" value="@{{user.vendor['device_sr_no']}}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">STATUS</label>
                                                <div class="col-sm-9">
                                                    <div class="input-group m-b alert-success">
                                                        <span class="input-group-btn">
                                                        <button type="button" class="btn btn-primary"><i class="fa fa-check"></i></button> 
                                                        </span>
                                                        <div class='col-lg-2 pad-t-7'><strong>Active</strong></div>
                                                        <!--<input type="text" class="form-control alert-success">-->
                                                    </div>
                                                    <!--<div role="alert" class="alert alert-success alert-icon alert-icon-border alert-dismissible">
                                                        <div class="icon"><span class="mdi mdi-check"></span></div>
                                                        <div class="message">
                                                            <strong>Active</strong>
                                                        </div>
                                                    </div>-->
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">GST NO.</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="gst_no" ng-value="user.vendor['gst_no']" ng-model="user.vendor['gst_no']" class="form-control err" ng-disabled="gstNoDisabled" ng-required="! gstNoDisabled">
                                                    <p ng-show="VendorProfileFrm.$submitted && VendorProfileFrm.gst_no.$invalid" class="err-mark">Please enter GST No.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group" ng-show="! bankNameDisabled">
                                                <label class="col-sm-3 control-label">Bank Name</label>
                                                <div class="col-sm-6">
                                                    <div isteven-multi-select input-model="banks" output-model="bank" button-label="name" item-label="name" tick-property="ticked" selection-mode="single" id="Bank" name="bank" class="multiselect-form-control" ng-class="{'err-mark': (VendorProfileFrm.$submitted && ! customRequiredCheck(bank))}">
                                                    </div>
                                                    <p ng-show="VendorProfileFrm.$submitted && ! customRequiredCheck(bank)" class="err-mark">Please select a bank.</p>
                                                </div>
                                            </div>
                                            <div class="form-group" ng-show="bankNameDisabled">
                                                <label class="col-sm-3 control-label">Bank Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="bank_name" ng-value="user.vendor_bank_account['bank_name']" ng-model="user.vendor_bank_account['bank_name']" class="form-control err" ng-disabled="bankNameDisabled">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group" ng-show="! accTypeDisabled">
                                                <label class="col-sm-3 control-label">Account Type</label>
                                                <div class="col-sm-9">
                                                    <select id="acc_type" name="acc_type" ng-options="acc_type.id as acc_type.acc_type for acc_type in acc_types" ng-model="acc_type" class="form-control err" required>
                                                        <option value="">None Selected</option>
                                                    </select>
                                                    <p ng-show="VendorProfileFrm.$submitted && VendorProfileFrm.acc_type.$invalid" class="err-mark">Please enter Account Type.</p>
                                                </div>
                                            </div>
                                            <div class="form-group" ng-show="accTypeDisabled">
                                                <label class="col-sm-3 control-label">Account Type</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="acc_type" ng-value="user.vendor_bank_account['acc_type_label']" ng-model="user.vendor_bank_account['acc_type_label']" class="form-control err" ng-disabled="accTypeDisabled">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Account No.</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="acc_no" ng-value="user.vendor_bank_account['acc_no']" ng-model="user.vendor_bank_account['acc_no']" class="form-control err" ng-disabled="accNoDisabled" ng-required="! accNoDisabled" isnumber>
                                                    <p ng-show="VendorProfileFrm.$submitted && VendorProfileFrm.acc_no.$invalid" class="err-mark">Please enter Account NO.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">IFSC Code</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="ifsc_code" ng-value="user.vendor_bank_account['ifsc_code']" ng-model="user.vendor_bank_account['ifsc_code']" class="form-control err" ng-disabled="ifscCodeDisabled" ng-required="! ifscCodeDisabled">
                                                    <p ng-show="VendorProfileFrm.$submitted && VendorProfileFrm.ifsc_code.$invalid" class="err-mark">Please enter IFSC Code.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" >Profile Picture <font color="red"> *</font></label>
                                                <div class="col-sm-9">
                                                  <input type="file" name="user_profile_url" file-model="myfile1" required>
                                                  <p ng-show="VendorProfileFrm.$submitted && VendorProfileFrm.user_profile_url.$invalid" class="err-mark">Please upload your profile picture.</p>
                                                </div>
                                              </div>
                                        </div>
                                    </div> 
                                        <div class="clearfix"></div>
                                        <div class="form-group">
                                          <div class="col-sm-3"></div>
                                          <div class="col-sm-9">
                                               <button type="submit" class="btn btn-primary btn-lg" ng-hide="submitDisabled">Save</button>
                                          </div>  
                                      </div>
                                    </div>    
                            <!--<div class="panel-heading text-center">Advance Details</div></div>-->
                                    
                              <div class="clearfix"></div>
                              
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
               <!--  -->
            </div>
         </div>
      </div>
   </div>
</div>
@stop
@section('javascript')
<script>
angular.module('DIPApp')
.controller('ProfileCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) { 
//window.s = $scope
$scope.user = {{ $userProfile }}
$scope.banks = {{ json_encode($banks) }}

console.log($scope.user.vendor)

$scope.accTypeDict = {
   0: 'Savings',
   1: 'Current'
}

formatProfile($scope.user)
$scope.states =  {{ json_encode(Config::get('constants.states')) }}
$scope.user.vendor.stateName = $scope.states[$scope.user.vendor.state];
$scope.zones = {{ json_encode(Config::get('constants.zones')) }}
$scope.user.vendor.zoneName = $scope.zones[$scope.user.vendor.zone];
$scope.vendorType =$scope.user.vendor.type.id;

$scope.typeDic = {
1: 'DISTRIBUTOR',
2: 'SUPER DISTRIBUTOR'
}

$scope.acc_types = [
  {id: 0, acc_type: "Savings"},
  {id: 1, acc_type: "Current"}
]

$scope.customRequiredCheck = customRequiredCheck

$scope.gstNoDisabled = false
$scope.bankNameDisabled = false
$scope.accTypeDisabled = false
$scope.accNoDisabled = false
$scope.ifscCodeDisabled = false
$scope.ifscCodeDisabled = false
$scope.submitDisabled = true 
disableFields($scope.user)

$scope.saveProfile=saveProfile;

function saveProfile (vendorObj) {
   if ($scope.VendorProfileFrm.$invalid || ! $scope.customRequiredCheck($scope.bank)) return

   var file1=$scope.myfile1

   var obj = {
      gst_no: vendorObj.vendor.gst_no,
      bank_id: $scope.bank[0].id,
      acc_type: vendorObj.vendor_bank_account.acc_type,
      acc_no: vendorObj.vendor_bank_account.acc_no,
      ifsc_code: vendorObj.vendor_bank_account.ifsc_code,
      file1:$scope.myfile1
   }

   var saveProfile=Upload.upload({
     url:'/users/'+vendorObj.id+'/actions/profile/update',
     method:"post",
     data:obj,
    });
    saveProfile.then(function(data)
    {
      console.log(data);
      toastr.success('Profile updated successfully')
      window.location.reload()
   },
   fail );

}

function formatProfile (p) {
   if (! p.vendor_bank_account) return
   p.vendor_bank_account.bank_name = $scope.banks.filter(function (b) {
      return b.id == p.vendor_bank_account.bank_id
   })[0].name
   p.vendor_bank_account.acc_type_label = $scope.accTypeDict[p.vendor_bank_account.acc_type]
}

function disableFields (user) {
   if (user.vendor.gst_no && user.vendor.gst_no != '') $scope.gstNoDisabled = true
   if (! $scope.gstNoDisabled) $scope.submitDisabled = false
   if (! user.vendor_bank_account) return $scope.submitDisabled = false
   if (user.vendor_bank_account.bank_id && user.vendor_bank_account.bank_id != '') $scope.bankNameDisabled = true   
   if (user.vendor_bank_account.acc_type && user.vendor_bank_account.acc_type != '') $scope.accTypeDisabled = true   
   if (user.vendor_bank_account.acc_no && user.vendor_bank_account.acc_no != '') $scope.accNoDisabled = true   
   if (user.vendor_bank_account.ifsc_code && user.vendor_bank_account.ifsc_code != '') $scope.ifscCodeDisabled = true 
   if (! $scope.gstNoDisabled || ! $scope.bankNameDisabled) $scope.submitDisabled = false  
   return
}

function customRequiredCheck (model, key) {
   if (! key)
      return (! model || model.length == 0) ? false : true
   return (! _.has(model, key) || model[key] == "") ? false : true
}

$scope.cities = {{ json_encode($cities) }}
$scope.user.vendor.cityName = ($scope.cities[$scope.user.vendor.city]) ? $scope.cities[$scope.user.vendor.city].name : 'N.A.';

function fail (err) {
sweetAlert('Error', 'Something went wrong', 'error')
}
}])
</script>
@stop