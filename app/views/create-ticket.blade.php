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
<div ng-controller="createTicketCtrl"  class="head-weight">
  <div class="row">
    <div class="col-md-12">
       <div class="panel panel-default">
        <!-- Page Title -->
        <div class="panel-heading">Create Ticket</div>
        <!-- /Page Title --> 
        <!-- Page Content -->
        <div class="tab-container ">
          <div class="tab-content ">
             <div class="panel panel-default panel-default panel-border-color panel-border-color-primary">
                 <div class="panel-body">
                     <form name="createTicketFrm" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed" ng-submit="createTicketRequest(user)" novalidate>

    
                      <div class="row">
                        
                        <div class="col-sm-8">
                          <div class="form-group">
                           <label class="col-sm-4 control-label">Type <font color="red">*</font></label>
                           <div class="col-sm-8">
                              <select  ng-model="type" class="form-control err" id="type" name="type" required>
                                <option value="">SELECT OPTION</option>
                                <option value="0">A</option>
                                <option value="1">B</option>
                                <option value="2">C</option>
                              </select>
                              <p ng-show="createTicketFrm.$submitted && createTicketFrm.type.$invalid" class="err-mark">Please select Type.</p>
                             </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-8">
                          <div class="form-group">
                              <label class="col-sm-4 control-label" >Title <font color="red"> *</font></label>
                              <div class="col-sm-8">
                                <input type="text" ng-model="title" name="title" class="form-control err" placeholder="Enter Title" required >
                                <p ng-show="createTicketFrm.$submitted && createTicketFrm.title.$invalid" class="err-mark">Please enter the Title.</p>
                              </div>
                            </div>
                        </div>
                      </div>

                  <div class="row">
                    <div class="col-sm-8">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Tx ID <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <input type="number" ng-model="tx_id" name="tx_id" class="form-control err" placeholder="Enter Transaction ID" required>
                          <p ng-show="createTicketFrm.$submitted && createTicketFrm.tx_id.$invalid" class="err-mark">Please enter the Transaction ID.</p>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                  <div class="row">
                    <div class="col-sm-8">
                      <div class="form-group">
                        <label class="col-sm-4 control-label" >Description <font color="red"> *</font></label>
                        <div class="col-sm-8">
                          <textarea rows="4" cols="50" ng-model="description" name="description" class="form-control err" placeholder="Enter Description" required ></textarea>
                          <p ng-show="createTicketFrm.$submitted && createTicketFrm.description.$invalid" class="err-mark">Please enter the Description.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                          <label class="col-sm-4 control-label" >Image Upload <font color="red"> *</font></label>
                          <div class="col-sm-8">
                            <input type="file" name="imageurl" file-model="myfile1" required>
                            <p ng-show="createTicketFrm.$submitted && createTicketFrm.imageurl.$invalid" class="err-mark">Please upload Image File.</p>
                          </div>
                        </div>
                    </div>

                  </div>

                  <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                          <div class="col-sm-4"></div>
                          <div class="col-sm-8">
                            <button type="submit" class="btn btn-primary btn-lg" ng-click="" style="width: 100%">Submit</button>
                          </div>
                        </div>
                    </div>

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
angular.module('DIPApp')

  .controller('createTicketCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) {
  window.s = $scope
  
}])
</script>
@stop