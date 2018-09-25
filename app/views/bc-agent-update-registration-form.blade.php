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
<div ng-controller="bcAgentuserCtrl"  class="head-weight">
  <div class="row">
    <div class="col-md-12">
       <div class="panel panel-default">
        <!-- Page Title -->
        <div class="panel-heading">BC Agent Update user Form</div>
        <!-- /Page Title --> 
        <!-- Page Content -->
        <div class="tab-container ">
          <div class="tab-content ">
             <div class="panel panel-default panel-default panel-border-color panel-border-color-primary">
                 <div class="panel-body">
                     <form name="bcAgentProfileFrm" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed" ng-submit="saveProfile(user)" novalidate>

  
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                        <label class="col-sm-4 control-label" >BC Agent Name <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="text" ng-model="bc_agent_name" name="bc_agent_name" value="@{{user.bcAgents['0'].bc_agent_name}}" class="form-control"  readonly="readonly" >
                        </div>
                      </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label class="col-sm-4 control-label" >Middle Name</label>
                            <div class="col-sm-8">
                              <input type="text" ng-model="middle_name" name="middle_name" class="form-control" value="@{{user.bcAgents['0'].middle_name}}" readonly="readonly">
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                              <label class="col-sm-4 control-label" >Last Name <font color="red"> *</font></label>
                              <div class="col-sm-8">
                                <input type="text" ng-model="last_name" name="last_name" class="form-control" value="@{{user.bcAgents['0'].last_name}}" readonly="readonly">
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label class="col-sm-4 control-label" >Mobile NO <font color="red"> *</font></label>
                            <div class="col-sm-8">
                              <input type="text" ng-model="mobile_number" name="mobile_number" class="form-control"  value="@{{user.bcAgents['0'].mobile_number}}" readonly="readonly">
                            </div>
                          </div>
                        </div>
                      </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Email ID</label>
                        <div class="col-sm-8">
                          <input type="email" ng-model="email_address" name="email_address" class="form-control" value="@{{user.bcAgents['0'].email_address}}" readonly="readonly">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >User PDF Form <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="file" name="registration_pdf_url" file-model="myfile1" required>
                          <p ng-show="bcAgentProfileFrm.$submitted && bcAgentProfileFrm.registration_pdf_url.$invalid" class="err-mark">Please upload Registration PDF Form.</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-5"></div>
                    <div class="col-sm-2">
                      <button type="submit" class="btn btn-primary btn-lg" ng-click="" style="width: 100%">Submit</button>
                    </div> 
                    <div class="col-sm-5"></div>
                  </div>                    
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

var bcAgents = {{json_encode($bcAgents) }}

angular.module('DIPApp')

  .controller('bcAgentuserCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) {
  window.s = $scope

      $scope.bcAgents = bcAgents
      console.log($scope.bcAgents)

      $scope.bc_agent_name=bcAgents['0'].bc_agent_name
      $scope.middle_name=bcAgents['0'].middle_name
      $scope.last_name=bcAgents['0'].last_name
      $scope.mobile_number=bcAgents['0'].mobile_number
      $scope.email_address=bcAgents['0'].email_address

      console.log(bcAgents['0'].email_address)

      $scope.saveProfile=saveProfile;

      function saveProfile (vendorObj) {

        var file1=$scope.myfile1;

        if ($scope.bcAgentProfileFrm.$valid)
        {
             var filter1={
             file1:$scope.myfile1
            }

          console.log(filter1)

             var saveProfile=Upload.upload({
              url:'/users/'+bcAgents['0'].id+'/actions/profile/registration/update',
              method:"post",
              data:filter1,
             });

             saveProfile.then(function(response)
             {
                console.log(response);
                if(response.status==200)
                {
                  sweetAlert('Success', 'BC Agent Form Updated Successfully!!.', 'success')
                  setTimeout(function () {
                  location.reload();
                }, 1500)

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
@stop