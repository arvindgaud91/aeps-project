<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="CreditRequestCtrl" class="head-weight">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default panel-border-color panel-border-color-primary m-b-0">
        <?php
            $domain_data = preg_replace('#^https?://#', '', Request::root());
        ?>
        @if($domain_data == 'am-tech.digitalindiapayments.com')
         <div class="panel-heading panel-heading-divider">From AM-TECH</div>
        @elseif(Auth::user()->parent_id != 8130)
        <div class="panel-heading panel-heading-divider">Settlement Request</div>
        @else
          <div class="panel-heading panel-heading-divider">From DIPL</div>
        @endif
        <div class="row">
          <div class="col-md-12" ng-show="settlement_requestchecks > 0">
            <div class="panel-default m-b-0">
              <div class="panel-body table-responsive">
                <form class="form-signin" name="creditRequestFrm" ng-submit="submit(creditRequest)" novalidate >
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        
                        <label class="control-label" style="text-transform: capitalize"><small>Last day Closing Balance</small></label>
                        <input type="text" ng-model="creditRequest.closing_balance" class="form-control ng-pristine ng-untouched ng-valid" ng-value="@{{closing_balance}}" name="closing_balance" disabled>
                        <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.closing_balance.$invalid)" class="err-mark">Please enter closing balance.</p>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        
                        <label class="control-label" style="text-transform: capitalize"><small>Available Balance</small></label>
                        <input type="text" ng-model="creditRequest.available_balance" class="form-control ng-pristine ng-untouched ng-valid" ng-value="@{{user_balance}}" name="available_balance" disabled>
                        <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.available_balance.$invalid)" class="err-mark">Please enter available balance.</p>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label"><small>Transaction Amount<font color="red"> * (Required)</font></small></label>
                        <input type="number" ng-model="creditRequest.transaction_amount" ng-change="changedValue(creditRequest.transaction_amount)" class="form-control" name="transaction_amount" placeholder="ENTER TRANSACTION BALANCE" required isnumber >
                        <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.transaction_amount.$invalid || invalidAmount(creditRequest.transaction_amount))" class="err-mark">Please enter a valid transaction amount.</p>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label"><small>Remaining Balance</small></label>
                      
                        
                        <input type="text" class="form-control" ng-model="remaining_balance"   name="remaining_balance" placeholder="REMAINING BALANCE" disabled>
                        <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.remaining_balance.$invalid)" class="err-mark">Please enter remaining balance.</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <button type="submit" class="btn btn-success" name="btn-credit-request"  ng-disabled="isDisabled"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;Submit</small></button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-12" ng-show="settlement_requestchecks == 0">
            <h4 style="margin-left:25px"> <a href="/settlement" >Please Request First For Settlement</a> <h4>
          </div>
        </div>
        <div class="row">
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
  var closing_balance={{ json_encode($closing_balance) }};
  var remaining_balance=0;
  var settlement_requestchecks={{json_encode($settlement_request)}};
  
  angular.module('DIPApp')
  .controller('CreditRequestCtrl', ['$scope', '$http', function ($scope, $http) {
   $scope.user_balance = user_balance
      // $scope.transaction_amount = transaction_amount
      $scope.closing_balance = closing_balance
      $scope.settlement_requestchecks=settlement_requestchecks

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
                $scope.isDisabled = true;
              req = Object.assign(creditRequest, {available_balance: user_balance,remaining_balance: remaining_balance})

              $http.post(`/post-settlement-request`, req)
              .then(data => {
                oldToastr.success("Request submitted successfully.")
          //sweetAlert('Success', 'Credit sent to the agent.', 'success')
          setTimeout(function () {
            location.href = '/settlement-report'
          }, 2000)
          //location.href = '/settlement-report'
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
            sweetAlert('Error', 'You have submitted request already.', 'error')
            return
          }
          if (err.data.code && err.data.code == 4) {
            sweetAlert('Error', 'Please add your bank details first.', 'error')
            return
          }
          if (err.status == 403 || err.status == 401) {
            sweetAlert('Error', 'This request is unauthorized.', 'error')
            return
          }
          sweetAlert('Error', 'Error. Try again.', 'error')
        })
            } else {
              sweetAlert("Cancelled", "Your request has been cancelled", "error");
            }
          });
      }

      function invalidAmount (amount) {
        if(amount>closing_balance){
          sweetAlert('Error', 'Your closing balance is not enough', 'error')
        }
        
        return amount >= 100 && amount <= (user_balance-100) && amount<user_balance && amount<closing_balance ? false : true
      }

      function fail (err) {
        
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
  </script>
  @stop
