<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="DebitRequestCtrl" class="head-weight">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default panel-border-color panel-border-color-primary">
        <div class="panel-heading panel-heading-divider">Wallet Debit Request Form</div>
        <div class="row">
          <div class="col-md-12">
            <div class=" panel-default">
              <div class="panel-body table-responsive">
                <form class="form-signin" name="debitRequestFrm" ng-submit="submit(debitRequest)" novalidate>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label" style="text-transform: capitalize"><small ng-show="child.vendor_details.type==1">Agent</small></label>
                        <label class="control-label" style="text-transform: capitalize"><small ng-show="child.vendor_details.type==2">Distributor</small></label>
                        <input type="text" ng-model="debitRequest.child" class="form-control" disabled name="agent" placeholder="Agent" >
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label"><small>Amount<font color="red"> * (Required)</font></small></label>
                        <input type="number" ng-model="debitRequest.amount" class="form-control" name="amount" placeholder="ENTER AMOUNT" required isnumber>
                        <p ng-show="debitRequestFrm.$submitted && (debitRequestFrm.amount.$invalid || invalidAmount(debitRequest.amount))" class="err-mark">Please enter an amount between 100 and 2500000.</p>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label"><small>Remarks</small></label>
                        <input type="text" class="form-control" ng-model="debitRequest.remarks" name="remarks" placeholder="REMARKS" required>
                        <p ng-show="debitRequestFrm.$submitted && (debitRequestFrm.remarks.$invalid && invalidAmount(debitRequestFrm.remarks))" class="err-mark">Please enter remarks.</p>
                        
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <button type="submit" class="btn btn-success" name="btn-debit-request" ng-disabled="disabled"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;DEBIT NOW</small></button>
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
angular.module('DIPApp')
  .controller('DebitRequestCtrl', ['$scope', '$http', function ($scope, $http) {
  //window.s = $scope;
  $scope.vendorDetails = {{ Auth::user()->vendorDetails }}
 
  $scope.child = {{ $child }}
   $scope.disabled= false;
  $scope.debitRequest = {
    child : $scope.child.name,
    child_id : $scope.child.id
  }
  $scope.vendorType = {{ json_encode($vendorType) }}
  
  $scope.submit = submit
  $scope.invalidAmount = invalidAmount
  
  function submit (debitRequest) {
    
    if ($scope.debitRequestFrm.$invalid || invalidAmount(debitRequest.amount)) return


    if (! confirm ("Are you sure?")) return
   /* sweetAlert({
            title: "Are you sure?",
            
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Debit it!",
            closeOnConfirm: true
          },*/
          //function(){
            //if ($scope.child.vendor_details.balance < debitRequest.amount) return sweetAlert("Error", "Insufficient Balance.", "error")

    if ($scope.child.vendor_details.balance < debitRequest.amount) return sweetAlert('Error', 'Insufficient Balance.', 'error')


$scope.disabled= true;
      req = Object.assign(debitRequest, {user_id: $scope.activeUserProfile.id})

      $http.post(`/api/v1/users/{{$child->id}}/actions/debit-wallet`, req)
        .then(data => {
          oldToastr.success("Debit sent to the agent.")
          // sweetAlert('Success', 'Credit sent to the agent.', 'success')
          
          setTimeout(function(){ location.href = $scope.vendorType.id == 2 ? "/agents" : $scope.vendorType.id == 3 ? "/distributors" : '/' }, 3000)
        }, function (err) {
          if (err.data.code && err.data.code == 1) {
            sweetAlert('Error', 'Amount is missing or is invalid. Please enter a number', 'error')
            return
          }
          if (err.data.code && (err.data.code == 2 || err.data.code == 3)) {
            sweetAlert('Error', 'The user or you is not a vendor.', 'error')
            return
          }
          if (err.data.code && err.data.code == 4) {
            sweetAlert('Insufficient balance', 'The vendor has insufficient balance.', 'error')
            return
          }
          if (err.data.code && err.data.code == 5) {
            sweetAlert('Debit Disable', ' Temporarily Debit Disable For This Agent.', 'error')
            return
          }
          if (err.status == 403 || err.status == 401) {
            sweetAlert('Error', 'This request is unauthorized.', 'error')
            return
          }
          sweetAlert('Error', 'Error. Try again.', 'error')
        })
         // })
    
      }

      function invalidAmount (amount) {
        return amount >= 100 && amount <= 2500000 ? false : true
      }

      function fail (err) {
        
        sweetAlert('Error', 'Something went wrong', 'error')
      }
}]);
</script>
@stop