<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="reportsCtrl" class="head-weight">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <!-- Page Title -->
                <div class="panel-heading">OTP </div>
                <!-- /Page Title -->
                <!-- Page Content -->
                <div class="tab-container ">
                    <div class="tab-content">
                        <div class="panel panel-default panel-default panel-border-color panel-border-color-primary">
                            <div class="panel-body">
                                <!-- Section Title -->
                                <div class="panel-heading">Receiver OTP </div>
                                <!-- /Section Title -->
                                <!-- Form -->
                                 @if (Session::has('error'))
                               <div class="alert alert-danger">{{ Session::get('error') }}</div>
                                @endif

                                <form name="OtpReceiverFrm" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed"  novalidate  ng-submit="submitOTP(data)">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" >OTP</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="otp" class="form-control err" placeholder="Enter otp" ng-model="data.otp" required>
                                                    
                                                </div>
                                            </div>
                                        <button type="submit" class="btn btn-primary btn-lg"  ng-disabled="isDisabled" >Submit</button>
                                            
                                        </div>
                                    </div>
                                    
                                </form>
                                <!-- /Form -->
                                <div class="col-md-6">
                                      <button ng-click="resendOTP({{$transaction_id}})" class="btn btn-primary btn-lg">Resend OTP</button>
                                </div>
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
    var transactions_id = {{ $transaction_id }}

</script>
<script>
angular.module('DIPApp')
.controller('reportsCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    
    $scope.resendOTP = resendOTP
    $scope.submitOTP = submitOTP
   
  
    function submitOTP (data) {
       if(data.otp){
        $scope.isDisabled = true;
        $http.post('/refund/otp/'+{{ $transaction_id }}+'/process',data)
          .then(function (msg) {
            if (msg.data.code == 2) {
              sweetAlert('Success', 'Refund creditted', 'success')
               setTimeout(function () {
                      window.location.href = '/transaction-reports'
                }, 1500)
            }
          }, function (err) {
            $scope.isDisabled = false;
            if (err.data.code == 1) {
              sweetAlert('Error', 'Invalid OTP', 'error')
              return
            }
            if (err.data.code == 404) {
              sweetAlert('Error', 'Transaction not found', 'error')
              return
            }
            
            sweetAlert('Error', 'Something went wrong. Try again later.', 'error')
          })
       }
        
    }

    function resendOTP (transaction_id) {
       
        $http.post('/refund/otp/'+transaction_id)
          .then(function (msg) {
            if (msg.data.code == 2) {
              sweetAlert('Success', 'OTP Send succefully', 'success')
               setTimeout(function () {
                      //window.location.href = '/transaction-reports'
                }, 1500)
            }
          }, function (err) {
           
            if (err.data.code == 1) {
              sweetAlert('Error', 'Phone number is not registered with us', 'error')
              return
            }
            
            sweetAlert('Error', 'Something went wrong. Try again later.', 'error')
          })
    }
   
                            
        
}])
</script>
@stop