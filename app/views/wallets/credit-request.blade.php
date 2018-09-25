<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
  <div ng-controller="CreditRequestCtrl" class="head-weight">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          <div class="panel-heading panel-heading-divider">Wallet Credit Request Form</div>

          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-body table-responsive">
                  <form class="form-signin" name="creditRequestFrm" ng-submit="submit(creditRequest)" novalidate>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label" style="text-transform: capitalize"><small ng-show="child.vendor_details.type==1">Agent</small></label>
                          <label class="control-label" style="text-transform: capitalize"><small ng-show="child.vendor_details.type==2">Distributor</small></label>
                          <input type="text" ng-model="creditRequest.child" class="form-control" disabled name="agent" placeholder="Agent" >
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Amount<font color="red"> * (Required)</font></small></label>
                          <input type="number" ng-model="creditRequest.amount" class="form-control" name="amount" placeholder="ENTER AMOUNT" required isnumber>
                          <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.amount.$invalid || invalidAmount(creditRequest.amount))" class="err-mark">Please enter an amount between 100 and 2500000.</p>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Remarks</small></label>
                          <!--input type="text" class="form-control" ng-model="creditRequest.remarks" name="remarks" placeholder="REMARKS"-->
                           <input type="text" class="form-control" ng-model="creditRequest.remarks" name="remarks" placeholder="REMARKS" required>
                        <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.remarks.$invalid && invalidAmount(creditRequestFrm.remarks))" class="err-mark">Please enter remarks.</p>
                        
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <button type="submit"  class="btn btn-success" name="btn-credit-request" ng-disabled="disabled"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;CREDIT NOW</small></button>
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
    .controller('CreditRequestCtrl', ['$scope', '$http', function ($scope, $http) {

      //window.s = $scope;
      $scope.disabled= false;
      $scope.vendorDetails = {{ Auth::user()->vendorDetails }}
      $scope.child = {{ $child }}
      $scope.creditRequest = {
        child : $scope.child.name,
        child_id : $scope.child.id
      }
      $scope.vendorType = {{ json_encode($vendorType) }}

      $scope.submit = submit
      $scope.invalidAmount = invalidAmount

      function submit (creditRequest) {
       
        if ($scope.creditRequestFrm.$invalid || invalidAmount(creditRequest.amount)) return
         if (! confirm ("Are you sure?")) return
        if ($scope.vendorDetails.balance < creditRequest.amount) return sweetAlert('Error', 'Insufficient Balance.', 'error')
          $scope.disabled= true;

        req = Object.assign(creditRequest, {user_id: $scope.activeUserProfile.id})

        $http.post(`/api/v1/users/${req.user_id}/actions/credit-request`, req)
        .then(data => {
          oldToastr.success("Credit sent to the agent.")
          // sweetAlert('Success', 'Credit sent to the agent.', 'success')
          location.href = $scope.vendorType.id == 2 ? "/agents" : $scope.vendorType.id == 3 ? "/distributors" : '/'
        }, function (err) {
          if (err.data.code && err.data.code == 1) {
            sweetAlert('Error', 'Missing data. Fill all the details please.', 'error')
            return
          }
          if (err.data.code && err.data.code == 2) {
            sweetAlert('Error', 'Insufficient Balance.', 'error')
            return
          }
          if (err.data.code && err.data.code == 4) {
            sweetAlert('Error', 'Temporarily Unavailable.', 'error')
            return
          }
          if (err.status == 403 || err.status == 401) {
            sweetAlert('Error', 'This request is unauthorized.', 'error')
            return
          }
          sweetAlert('Error', 'Error. Try again.', 'error')
        })
      }

      function invalidAmount (amount) {
        return amount >= 100 && amount <= 2500000 ? false : true
      }

      function fail (err) {
        
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
