<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')


<div ng-controller="bcAgentRegistrationCtrl"  class="head-weight">
  <div class="row">
    <div class="col-md-12">
       <div class="panel panel-default">
        <!-- Page Title -->
        <div class="panel-heading">BC Agent Registration</div>
        <!-- /Page Title --> 
        <!-- Page Content -->
        <div class="tab-container ">
          <div class="tab-content ">
             <div class="panel panel-default panel-default panel-border-color panel-border-color-primary">
                 <div class="panel-body">
                     <form name="bcAgentFrm" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed" ng-submit="bcAgentRequest(onboarding)" novalidate>
                      
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                        <label class="col-sm-4 control-label" >BC Agent Name<font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input value="" type="text" ng-model="bc_agent_name" name="bc_agent_name" class="form-control err" placeholder="Enter BC Agent Name" required >
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.bc_agent_name.$invalid" class="err-mark">Please enter the BC Agent Name.</p>
                        </div>
                      </div>
                        </div>
                        <div class="col-sm-6">

                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                        <label class="col-sm-4 control-label" >Middle Name</label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="middle_name" name="middle_name" class="form-control err" placeholder="Enter Middle Name">
                        </div>
                      </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                        <label class="col-sm-4 control-label" >Last Name <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="last_name" name="last_name" class="form-control err" placeholder="Enter Last Name" required >
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.last_name.$invalid" class="err-mark">Please enter the Last name.</p>
                        </div>
                      </div>
                        </div>
                      </div>
                  
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Comapny Name <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="company_name" name="company_name" class="form-control err" placeholder="Enter Comapny Name" required >
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.company_name.$invalid" class="err-mark">Please enter the Company name.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Address <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="address" name="address" class="form-control err" placeholder="Enter Address" required >
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.address.$invalid" class="err-mark">Please enter the Address.</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Area <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="area" name="area" class="form-control err" placeholder="Enter Area" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.area.$invalid" class="err-mark">Please enter the Area.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >City <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="city" name="city" class="form-control err" placeholder="Enter City" required >
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.city.$invalid" class="err-mark">Please enter the City.</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >District <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="district" name="district" class="form-control err" placeholder="Enter District" required >
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.district.$invalid" class="err-mark">Please enter the District.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >State <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="state" name="state" class="form-control err" placeholder="Enter State" required >
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.state.$invalid" class="err-mark">Please enter the State.</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Pin Code <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="pincode" name="pincode" class="form-control err" placeholder="Enter Pin Code" required isvalidpincode>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.pincode.$invalid" class="err-mark">Please enter the Pin Code.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Mobile NO <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="mobile_number" name="mobile_number" class="form-control err" placeholder="Enter Mobile Number" required >
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.mobile_number.$invalid" class="err-mark">Please enter the Mobile Number.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Local Address <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="laddress" name="laddress" class="form-control err" placeholder="Enter Local Address" required >
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.laddress.$invalid" class="err-mark">Please enter the Local Address.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Local Area <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="larea" name="larea" class="form-control err" placeholder="Enter Pin Code" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.larea.$invalid" class="err-mark">Please enter the Local Area.</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Local City Name <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="lcity" name="lcity" class="form-control err" placeholder="Enter Local City Name" required >
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.lcity.$invalid" class="err-mark">Please enter the Local City Name.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Local District <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="ldistrict" name="ldistrict" class="form-control err" placeholder="Enter Local District"  required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.ldistrict.$invalid" class="err-mark">Please enter the Local District.</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Local State Name <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="lstate" name="lstate" class="form-control err" placeholder="Enter Local State Name" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.lstate.$invalid" class="err-mark">Please enter the Local State Name.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                        <label class="col-sm-4 control-label" >Local Pin Code <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="lpincode" name="lpincode" class="form-control err" placeholder="Enter Local Pin Code" required isvalidpincode>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.lpincode.$invalid" class="err-mark">Please enter the Local Pin Code.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Telephone NO</label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="telephone" name="telephone" class="form-control err" placeholder="Enter Telephone Number" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Alternate NO</label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="alternate_number" name="alternate_number" class="form-control err" placeholder="Enter Alternate NO" >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Email ID</label>
                        <div class="col-sm-8">
                          <input type="email" ng-model="email_address" name="email_address" class="form-control err" placeholder="Enter Email ID" required ischar>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.email_address.$invalid" class="err-mark">Please enter the Email ID.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Date of Birth <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <div class="input-group date" >
                            <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-touched" placeholder="Enter Date of Birth" ng-model="date_of_birth" name="date_of_birth" id="datetimepicker6" ng-change="value=date_of_birth" required="">
                            <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                          </div>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.date_of_birth.$invalid" class="err-mark">Please enter Date of Birth.</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                       <label class="col-sm-4 control-label">ID Card Type <font color="red">*</font></label>
                       <div class="col-sm-8">
                          <select  ng-model="id_card_type" class="form-control err" id="id_card_type" name="id_card_type" required>
                            <option value="">SELECT OPTION</option>
                            <option value="0">Pancard</option>
                          </select>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.id_card_type.$invalid" class="err-mark">Please select ID Card Type.</p>
                         </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Pancard <font color="red">*</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="pancard" name="pancard" class="form-control err" placeholder="Enter Pancard" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.pancard.$invalid" class="err-mark">Please enter the Pancard.</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Shop Address <font color="red">*</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="saddress" name="saddress" class="form-control err" placeholder="Enter Shop Address" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.saddress.$invalid" class="err-mark">Please enter the Shop Address.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Shop Area <font color="red">*</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="sarea" name="sarea" class="form-control err" placeholder="Enter Shop Area" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.sarea.$invalid" class="err-mark">Please enter the Shop Address.</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Shop City Name <font color="red">*</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="scity" name="scity" class="form-control err" placeholder="Enter Shop City Name" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.scity.$invalid" class="err-mark">Please enter the Shop City Name.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Shop District <font color="red">*</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="sdistrict" name="sdistrict" class="form-control err" placeholder="Enter Shop District" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.sdistrict.$invalid" class="err-mark">Please enter the Shop District.</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Shop State Name <font color="red">*</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="sstate" name="sstate" class="form-control err" placeholder="Enter Shop State Name" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.sstate.$invalid" class="err-mark">Please enter the Shop State Name.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Shop Pin Code <font color="red">*</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="spincode" name="spincode" class="form-control err" placeholder="Enter Shop Pin Code" required isvalidpincode>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.spincode.$invalid" class="err-mark">Please enter the Shop Pin Code.</p>
                        </div>
                      </div>  
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >IFSC Code <font color="red">*</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="ifsc" name="ifsc" class="form-control err" placeholder="Enter IFSC Code" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.ifsc.$invalid" class="err-mark">Please enter the IFSC Code.</p>
                        </div>
                      </div>  
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Account Number <font color="red">*</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="account_number" name="account_number" class="form-control err" placeholder="Enter Account Number" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.account_number.$invalid" class="err-mark">Please enter the Account Number.</p>
                        </div>
                      </div> 
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >No of transactions per day <font color="red">*</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="no_of_transactions_per_day" name="no_of_transactions_per_day" class="form-control err" placeholder="Enter No of transactions per day" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.no_of_transactions_per_day.$invalid" class="err-mark">Please enter the No of transactions per day.</p>
                        </div>
                      </div>  
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Transfer Amount per day <font color="red">*</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="transfer_amount_per_day" name="transfer_amount_per_day" class="form-control err" placeholder="Enter Account Number" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.transfer_amount_per_day.$invalid" class="err-mark">Please enter the Transfer Amount per day.</p>
                        </div>
                      </div> 
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                       <label class="col-sm-4 control-label">Wallet Loading/Withdrawl <font color="red"> *</font></label>
                       <div class="col-sm-8">
                          <select  ng-model="wallet_loading" class="form-control err" id="wallet_loading" name="wallet_loading" required>
                            <option value="">SELECT OPTION</option>
                            <option value="1">Only Remittance</option>
                            <option value="2">Non-Individual</option>
                          </select>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.wallet_loading.$invalid" class="err-mark">Please select Wallet Loading/Withdrawl.</p>
                         </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                       <label class="col-sm-4 control-label">BC-Agent Type</label>
                       <div class="col-sm-8">
                          <select  ng-model="bc_agent_type" class="form-control err" id="bc_agent_type" name="bc_agent_type">
                            <option value="">SELECT OPTION</option>
                            <option value="1">Remittance(BC-Agent)</option>
                            <option value="2">Non-Individual</option>
                            </select>
                         </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                       <label class="col-sm-4 control-label">AEPS Transactions Allowed</label>
                       <div class="col-sm-8">
                          <select  ng-model="aeps_transactions_allowed" class="form-control err" id="aeps_transactions_allowed" name="aeps_transactions_allowed" required>
                            <option value="">SELECT OPTION</option>
                            <option value="1">Only Remittance</option>
                            <option value="2">Non-Individual</option>
                          </select>
                         </div>
                      </div>
                    </div>
                    <div class="col-sm-6">

                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                       <label class="col-sm-4 control-label">Parent Flag</label>
                       <div class="col-sm-8">
                          <select  ng-model="parent_flag" class="form-control err" id="parent_flag" name="parent_flag" required>
                            <option value="">SELECT OPTION</option>
                            <option value="1">Only Remittance</option>
                            <option value="2">Non-Individual</option>
                          </select>
                         </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Parent Agent ID</label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="parent_agent_id" name="parent_agent_id" class="form-control err" placeholder="Enter Parent Agent ID" required>
                        </div>
                      </div> 
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Agent Account Name <font color="red">*</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="agent_account_name" name="agent_account_name" class="form-control err" placeholder="Enter Agent Account Name" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.agent_account_name.$invalid" class="err-mark">Please enter the Agent Account Name.</p>
                        </div>
                      </div>  
                    </div>
                    <div class="col-sm-6">

                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >KYC Form <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="file" name="kycformurl" file-model="myfile1" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.kycformurl.$invalid" class="err-mark">Please select upload KYC Form.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Address Proof <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="file" name="addressproofurl" file-model="myfile2" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.addressproofurl.$invalid" class="err-mark">Please select upload address proof.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">

                        <label class="col-sm-4 control-label" >ID Proof <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="file" name="idproofurl" file-model="myfile3" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.idproofurl.$invalid" class="err-mark">Please select upload id proof.</p>
                        </div>
                      </div>
                    </div>

                    
                  </div>

                    <div class="col-sm-5"></div>
                  <div class="col-sm-2">
                    <button type="submit" class="btn btn-primary btn-lg" ng-click="" style="width: 100%">Submit</button>
                  </div> 
                  <div class="col-sm-5"></div>
                     </form>
                    <!-- /Form -->
                   </div>
                 </div>
             </div>
          </div>
        </div>
        <!-- /Page Content -->
       </div>
    </div>
  </div>
</div>
@stop
@section('javascript')
<script>
var dob_onchange;
angular.module('DIPApp')

  .controller('bcAgentRegistrationCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) {
  window.s = $scope
  $scope.bcAgentRequest=bcAgentRequest;

      $scope.assign = function() {
        $scope.modelValue = $('#datetimepicker6').val();
      }

      function bcAgentRequest(onboarding)
      {

        $scope.date_of_birth=dob_onchange

        var file1=$scope.myfile1;
        var file2=$scope.myfile2;
        var file3=$scope.myfile3;

        if ($scope.bcAgentFrm.$valid)
        {
             var filter1={
             bc_agent_name:$scope.bc_agent_name,
             middle_name:$scope.middle_name,
             last_name:$scope.last_name,
             company_name:$scope.company_name,
             address:$scope.address,
             area:$scope.area,
             city:$scope.city,
             district:$scope.district,
             state:$scope.state,
             pincode:$scope.pincode,
             mobile_number:$scope.mobile_number,
             laddress:$scope.laddress,
             larea:$scope.larea,
             lcity:$scope.lcity,
             ldistrict:$scope.ldistrict,
             lstate:$scope.lstate,
             lpincode:$scope.lpincode,
             telephone:$scope.telephone,
             alternate_number:$scope.alternate_number,
             email_address:$scope.email_address,
             date_of_birth:$scope.date_of_birth,
             id_card_type:$scope.id_card_type,
             pancard:$scope.pancard,
             saddress:$scope.saddress,
             sarea:$scope.sarea,
             scity:$scope.scity,
             sdistrict:$scope.sdistrict,
             sstate:$scope.sstate,
             spincode:$scope.spincode,
             ifsc:$scope.ifsc,
             account_number:$scope.account_number,
             no_of_transactions_per_day:$scope.no_of_transactions_per_day,
             transfer_amount_per_day:$scope.transfer_amount_per_day,
             wallet_loading:$scope.wallet_loading,
             bc_agent_type:$scope.bc_agent_type,
             aeps_transactions_allowed:$scope.aeps_transactions_allowed,
             parent_flag:$scope.parent_flag,
             parent_agent_id:$scope.parent_agent_id,
             agent_account_name:$scope.agent_account_name,
             file1:$scope.myfile1,
             file2:$scope.myfile2,
             file3:$scope.myfile3
          }

          console.log(filter1)

             var bcAgentRequest=Upload.upload({
              url:'/postBcagent',
              method:"post",
              data:filter1,
             });

             bcAgentRequest.then(function(response)
             {
                console.log(response);
                if(response.status==200)
                {
                  sweetAlert('Success', 'BC Agent Form Submitted Successfully!!.', 'success')
              // setTimeout(function () {
              //   location.reload();
              // }, 1500)

                }else{
                sweetAlert('Error', 'Something Is Wrong.', 'error')
                }
            }); 
        }else
        {
          sweetAlert('Error', 'Something Is Wrong.', 'error')
        }
      }
      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
  
}])
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#datetimepicker6').datetimepicker({ format: 'MM/DD/YYYY',maxDate:new Date()});
        $("#datetimepicker6").on("dp.change", function (e) { 
      //alert($("#datetimepicker6").val());
      dob_onchange=$("#datetimepicker6").val();
    });
    });
</script>
@stop