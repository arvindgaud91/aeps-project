<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
  <div ng-controller="CreditRequestCtrl" class="head-weight">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          <div class="panel-heading panel-heading-divider">AEPS TO DMT Form <span class="pull-right">AEPS to DMT Request will be approved in between 10:00 am to 7:00 pm</span></div>

          <div class="row">
            <div class="col-md-12">
              
              <div class="panel-default">
                <div class="panel-body table-responsive">
                  <form class="form-signin" name="creditRequestFrm" ng-submit="submit(creditRequest)" novalidate>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          
                          <label class="control-label" style="text-transform: capitalize"><small>Available Balance</small></label>
                          <input type="text" ng-model="creditRequest.available_balance" class="form-control ng-pristine ng-untouched ng-valid" ng-value="@{{user_balance}}" name="available_balance" disabled>
                          <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.available_balance.$invalid)" class="err-mark">Please enter available balance.</p>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Transaction Amount<font color="red"> * (Required)</font></small></label>
                          <input type="number" ng-model="creditRequest.transaction_amount" ng-change="changedValue(creditRequest.transaction_amount)" class="form-control" name="transaction_amount" placeholder="ENTER TRANSACTION BALANCE" required isnumber >
                          <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.transaction_amount.$invalid || invalidAmount(creditRequest.transaction_amount))" class="err-mark">Please enter a valid transaction amount.</p>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Remaining Balance</small></label>
                          <!--input type="text" class="form-control" ng-model="creditRequest.remarks" name="remarks" placeholder="REMARKS"-->
                        
                           <input type="text" class="form-control" ng-model="remaining_balance"   name="remaining_balance" placeholder="REMAINING BALANCE" disabled>
                        <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.remaining_balance.$invalid)" class="err-mark">Please enter remaining balance.</p>
                        
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <button type="submit" class="btn btn-success" name="btn-credit-request" ng-hide="isHide"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;Submit</small></button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- End Row -->
        </div>
      </div>
    </div>
  </div>
@stop

@section('javascript')
<script>
var user_balance={{ json_encode($user_balance) }};
var remaining_balance=0;
 
  angular.module('DIPApp')
    .controller('CreditRequestCtrl', ['$scope', '$http', function ($scope, $http) {
       $scope.user_balance = user_balance
      // $scope.transaction_amount = transaction_amount
       

       $scope.changedValue = function (transaction_amount)
    {

          remaining_balance=user_balance-transaction_amount
         $scope.remaining_balance=remaining_balance

    }
      $scope.submit = submit
      $scope.invalidAmount = invalidAmount
      
      function submit (creditRequest) {
         
        if ($scope.creditRequestFrm.$invalid || invalidAmount(creditRequest.transaction_amount)) return
          
          sweetAlert({
            title: "Are you sure?",
            text: "You will not be able to revert this transaction!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, send it!",
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: true,
            closeOnCancel: false
          },
          function(isConfirm) {
            if (isConfirm) {
              $scope.isHide=true;
        req = Object.assign(creditRequest, {available_balance: user_balance,remaining_balance: remaining_balance})

        $http.post(`/dmt-transfer-request`, req)
        .then(data => {
          oldToastr.success("Request submitted successfully.")
          //console.log(data)
          // sweetAlert('Success', 'Credit sent to the agent.', 'success')
        location.href = '/dmt-transfer-report'
        }, function (err) {
         
          if (err.data.code && err.data.code == 1) {
            sweetAlert('Error', 'Missing data. Fill all the details please.', 'error')
            return
          }
          if (err.data.code && err.data.code == 2) {
            sweetAlert('Error', 'Insufficient Balance.', 'error')
            return
          }
          if (err.data.code && err.data.code == 3) {
            sweetAlert('Error', 'Vendor does not exist in DMT.', 'error')
            return
          }
          if (err.status == 403 || err.status == 401) {
            sweetAlert('Error', 'This request is unauthorized.', 'error')
            return
          }
          sweetAlert('Error', ' Error. Try again.', 'error')
        })
            } else {
              sweetAlert("Cancelled", "Your request has been cancelled", "error");
            }
          });

        
      }

      function invalidAmount (amount) {
        return amount >= 100 && amount <= (user_balance-100) ? false : true
      }

      function fail (err) {
        
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
