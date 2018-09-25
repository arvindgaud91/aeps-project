<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')

<style>
.agentRole li {
    list-style-type: none;
    float: left;
    margin-right: 10px;
}
.agentRole li input {
    margin: 0 5px;
    vertical-align: middle;
}
</style>
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
                            <label class="col-sm-4 control-label" >Service Required <font color="red"> *</font></label>
                            <div class="col-sm-8">
                              <ul class="agentRole" >
                                <li><input type="checkbox" name="AgentRole" ng-true-value="'aeps'" ng-false-value="false" ng-model="services['aeps']"  class="err" >AEPS</li>
                                <li><input type="checkbox" name="AgentRole" ng-true-value="'dmt'" ng-false-value="false" ng-model="services['dmt']" class="err" >DMT
                                </li>
                              </ul>
                            <div class="clearfix"></div>
                            <p ng-show="bcAgentFrm.$submitted && checkAgentRole(services)" class="err-mark">Please select an Services.</p>
                            </div>
                          </div>

                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label class="col-sm-4 control-label" >Device Information IMEI No</label>
                            <div class="col-sm-8">
                              <input type="text" ng-model="imei_no" name="imei_no" class="form-control err" maxlength="15" placeholder="Enter Device Information IMEI 15 Digit No">
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                        <label class="col-sm-4 control-label" >BC Agent Name <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input value="" type="text" ng-model="bc_agent_name" name="bc_agent_name" class="form-control err" placeholder="Enter BC Agent Name" required >
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.bc_agent_name.$invalid" class="err-mark">Please enter the BC Agent Name.</p>
                        </div>
                      </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label class="col-sm-4 control-label" >Middle Name</label>
                            <div class="col-sm-8">
                              <input type="text" ng-model="middle_name" name="middle_name" class="form-control err" placeholder="Enter Middle Name">
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                              <label class="col-sm-4 control-label" >Last Name <font color="red"> *</font></label>
                              <div class="col-sm-8">
                                <input type="text" ng-model="last_name" name="last_name" class="form-control err" placeholder="Enter Last Name" required >
                                <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.last_name.$invalid" class="err-mark">Please enter the Last name.</p>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                           <label class="col-sm-4 control-label">Gender <font color="red">*</font></label>
                           <div class="col-sm-8">
                              <select  ng-model="gender" class="form-control err" id="gender" name="gender" required>
                                <option value="">SELECT OPTION</option>
                                <option value="0">Male</option>
                                <option value="1">Female</option>
                                <option value="2">Transgender</option>
                              </select>
                              <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.gender.$invalid" class="err-mark">Please select Gender.</p>
                             </div>
                          </div>
                        </div>
                      </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Name of the Establishment <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="establishment" name="establishment" class="form-control err" placeholder="Enter Name of the Establishment" required>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.establishment.$invalid" class="err-mark">Please enter Name of the Establishment.</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Education <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="education" name="education" class="form-control err" placeholder="Enter Education" required >
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.education.$invalid" class="err-mark">Please enter the Education.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label class="col-sm-2 control-label" >Outlet Address <font color="red"> *</font></label>
                        <div class="col-sm-10">
                          <input type="text" ng-model="address" name="address" class="form-control err" placeholder="Enter Outlet Address" required >
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
                        <label class="col-sm-4 control-label" >Mobile No. <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="mobile_number" name="mobile_number" class="form-control err" placeholder="Enter Mobile Number" required isphoneno>
                          <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.mobile_number.$invalid" class="err-mark">Please enter the Mobile Number.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="col-sm-4 control-label" >Resedential Address <font color="red"> *</font></label>
                          <div class="col-sm-8">
                            <input type="text" ng-model="laddress" name="laddress" class="form-control err" placeholder="Enter Local Address" required >
                            <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.laddress.$invalid" class="err-mark">Please enter the Local Address.</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="col-sm-4 control-label" >Resedential Area <font color="red"> *</font></label>
                          <div class="col-sm-8">
                            <input type="text" ng-model="larea" name="larea" class="form-control err" placeholder="Enter Resedential Area" required>
                            <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.larea.$invalid" class="err-mark">Please enter the Resedential Area.</p>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="col-sm-4 control-label" >Resedential City Name <font color="red"> *</font></label>
                          <div class="col-sm-8">
                            <input type="text" ng-model="lcity" name="lcity" class="form-control err" placeholder="Enter Local City Name" required >
                            <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.lcity.$invalid" class="err-mark">Please enter the Local City Name.</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="col-sm-4 control-label" >Resedential District <font color="red"> *</font></label>
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
                          <label class="col-sm-4 control-label" >Resedential State Name <font color="red"> *</font></label>
                          <div class="col-sm-8">
                            <input type="text" ng-model="lstate" name="lstate" class="form-control err" placeholder="Enter Local State Name" required>
                            <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.lstate.$invalid" class="err-mark">Please enter the Local State Name.</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                          <div class="form-group">
                          <label class="col-sm-4 control-label" >Resedential Pin Code <font color="red"> *</font></label>
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
                            <input type="text" ng-model="alternate_number" name="alternate_number" class="form-control err" placeholder="Enter Alternate Number">
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
                          <label class="col-sm-4 control-label" >Description of Services at present Outlet <font color="red">*</font></label>
                          <div class="col-sm-8">
                            <input type="text" ng-model="description_of_outlet" name="description_of_outlet" class="form-control err" placeholder="Enter Description of Services at present Outlet" required>
                            <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.description_of_outlet.$invalid" class="err-mark">Please enter the Description of Services at present Outlet.</p>
                          </div>
                        </div> 
                      </div>
                    
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="col-sm-4 control-label" >PAN No.<font color="red">*</font></label>
                          <div class="col-sm-8">
                            <input type="text" ng-model="pancard" name="pancard" class="form-control err" placeholder="Enter Pancard" required>
                            <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.pancard.$invalid" class="err-mark">Please enter the PAN No.</p>
                          </div>
                        </div>
                      </div>
                    
                    </div>

                    <div class="row">
                      
                      <div class="col-sm-6">
                        <div class="form-group">
                         <label class="col-sm-4 control-label">Oprating Hours <font color="red">*</font></label>
                         <div class="col-sm-8">
                            <select  ng-model="operating_hours" class="form-control err" id="operating_hours" name="operating_hours" required>
                              <option value="">SELECT OPTION</option>
                              <option value="0">8:00 AM to 5:00 PM</option>
                              <option value="1">9:00 AM to 6:00 PM</option>
                              <option value="2">10:00 AM to 7:00 PM</option>
                              <option value="3">11:00 AM to 8:00 PM</option>
                            </select>
                            <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.operating_hours.$invalid" class="err-mark">Please select Oerating Hours.</p>
                           </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                         <label class="col-sm-4 control-label">Weekly-Off <font color="red">*</font></label>
                         <div class="col-sm-8">
                            <select  ng-model="weekly_off" class="form-control err" id="weekly_off" name="weekly_off" required>
                              <option value="">SELECT OPTION</option>
                              <option value="0">None</option>
                              <option value="1">SUNDAY</option>
                              <option value="2">MONDAY</option>
                              <option value="3">TUESDAY</option>
                              <option value="4">WEDNESDAY</option>
                              <option value="5">THURSDAY</option>
                              <option value="6">FRIDAY</option>
                              <option value="7">SATURDAY</option>
                            </select>
                            <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.weekly_off.$invalid" class="err-mark">Please select Weekly-Off.</p>
                           </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="col-sm-4 control-label" >Bank Name <font color="red">*</font></label>
                          <div class="col-sm-8">
                            <input type="text" ng-model="bank_name" name="bank_name" class="form-control err" placeholder="Enter Bank Name" required>
                            <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.bank_name.$invalid" class="err-mark">Please enter the Bank Name.</p>
                          </div>
                        </div> 
                      </div>
                      <div class="col-sm-6">
                            <div class="form-group">
                             <label class="col-sm-4 control-label">Account Type <font color="red">*</font></label>
                             <div class="col-sm-8">
                                <select  ng-model="account_type" class="form-control err" id="account_type" name="account_type" required>
                                  <option value="">SELECT OPTION</option>
                                  <option value="0">Current</option>
                                  <option value="1">Saving</option>
                                </select>
                                <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.account_type.$invalid" class="err-mark">Please select Account Type.</p>
                               </div>
                            </div>
                          </div>
                    </div>
                    
                    <div class="row">
                      
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="col-sm-4 control-label" >Account Number <font color="red">*</font></label>
                          <div class="col-sm-8">
                            <input type="text" ng-model="account_number" name="account_number" class="form-control err" placeholder="Enter Account Number" required>
                            <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.account_number.$invalid" class="err-mark">Please enter the Account Number.</p>
                          </div>
                        </div> 
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="col-sm-4 control-label" >IFSC Code <font color="red">*</font></label>
                          <div class="col-sm-8">
                            <input type="text" ng-model="ifsc" name="ifsc" class="form-control err" placeholder="Enter IFSC Code" required>
                            <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.ifsc.$invalid" class="err-mark">Please enter the IFSC Code.</p>
                          </div>
                        </div>  
                      </div>
                    </div>


                    <div class="row">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label class="col-sm-4 control-label" >Address Proof <font color="red"> *</font></label>
                          <div class="col-sm-8">
                            <input type="file" name="addressproofurl" file-model="myfile2" required>
                            <p ng-show="bcAgentFrm.$submitted && bcAgentFrm.addressproofurl.$invalid" class="err-mark">Please select upload address proof. </p>
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
                      <div class="row">
                        <div class="col-sm-4">
                          <div class="form-group">
                            <label class="col-sm-4 control-label" >Applicant Recent Photo</label>
                            <div class="col-sm-8">
                              <input type="file" name="profileurl" file-model="myfile4">
                            </div>
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

      $scope.services={
        'aeps':false,
        'dmt':false
      };
      $scope.checkAgentRole=checkAgentRole



      function bcAgentRequest(onboarding)
      {

        $scope.date_of_birth=dob_onchange
        var file2=$scope.myfile2;
        var file3=$scope.myfile3;
        var file4=$scope.myfile4;
        var filter1={
           bc_agent_name:$scope.bc_agent_name,
           middle_name:$scope.middle_name,
           last_name:$scope.last_name,
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
           date_of_birth:dob_onchange,
           description_of_outlet:$scope.description_of_outlet,
           pancard:$scope.pancard,
           ifsc:$scope.ifsc,
           account_number:$scope.account_number,
           services_aeps:$scope.services.aeps,
           services_dmt:$scope.services.dmt,
           imei_no:$scope.imei_no,
           establishment:$scope.establishment,
           education:$scope.education,
           gender:$scope.gender,
           operating_hours:$scope.operating_hours,
           weekly_off:$scope.weekly_off,
           bank_name:$scope.bank_name,
           account_type:$scope.account_type,
           file2:$scope.myfile2,
           file3:$scope.myfile3,
           file4:$scope.myfile4
        }

          console.log(filter1)
          
           var bcAgentRequest=Upload.upload({
            url:'/postBcagent',
            method:"post",
            data:filter1,
           });

           bcAgentRequest.then(function(response)
           {
            alert('ssss')
              console.log(response);
              if(response.status==200)
              {
                sweetAlert('Success', 'BC Agent Form Submitted Successfully!!.', 'success')
                setTimeout(function () {
                  location.reload();
                }, 1500)

              }
              else{
                sweetAlert('Error', 'Something Is Wrong.', 'error')
              }
          },
          fail )
      }

      function checkAgentRole(service){
        if(service['aeps']==false && service['dmt']==false
        ||service['aeps']==undefined && service['dmt']==false
        ||service['aeps']==false && service['dmt']==undefined
        ||service['aeps']==undefined  && service['dmt']==undefined
        ){
          return true;
        }
        return false
      }

      function fail (err) {
          console.log(err.status)
          if(err.status==401){
            sweetAlert('Error', 'Mobile Number Already Exists!', 'error')
          }else if(err.status==402){
            sweetAlert('Error', 'Email ID Already Exists!', 'error')
          }else if(err.status==403){
            sweetAlert('Error', 'PAN Number Already Exists!', 'error')
          }else{
            sweetAlert('Error', 'Something Is Wrong.', 'error')
          }
      }
  
}])
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#datetimepicker6').datetimepicker({ format: 'YYYY-MM-DD',maxDate:new Date()});
        $("#datetimepicker6").on("dp.change", function (e) { 
      //alert($("#datetimepicker6").val());
      dob_onchange=$("#datetimepicker6").val();
    });
    });
</script>
@stop