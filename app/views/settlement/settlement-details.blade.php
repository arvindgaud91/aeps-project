<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
  <div ng-controller="CreditRequestCtrl" class="head-weight">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          <div class="panel-heading panel-heading-divider">Settlement Account Details</div>

          <div class="row">
          <div class="col-md-12" ng-show="settlement_details.length> 0">
              <div class="panel-default panel-table m-b-0">
                
                <div class="panel-body table-responsive"> 
                                                                    
                  <!-- <table ng-show="transactions.length > 0" class="table table-striped table-borderless"> -->
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Beneficiary Name</th>
                        <th>Bank Name</th>
                        <th>Account Number</th>
                        <th>IFSC Coder</th>
                       
                        <th>Settlement Type</th>
                    
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="tx in settlement_details">
                        <td>@{{tx.beneficiary_name}}</td>
                        <td>@{{tx.bank_name }}</td>
                        <td>@{{tx.account_number}}</td>
                        <td>@{{tx.ifsc_code}}</td>
                        <td>@{{(tx.settlement_type==0) ? 'Manual' : 'Auto'}}</td>
                        <td>@{{tx.created_at | date:"dd/MM/yyyy 'at' h:mma" }}</td>
                                                
                        
                      </tr>
                    </tbody>
                  </table>
                 
                </div>
              </div>
            </div>
            <div class="col-md-12" ng-hide="settlement_details.length>0" >
              <div class="panel panel-default m-b-0">
                <div class="panel-body table-responsive">
                  <form class="form-signin" name="creditRequestFrm" ng-submit="submit(creditRequest)" novalidate>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          
                          <label class="control-label" style="text-transform: capitalize"><small>Beneficiary Name</small></label>
                          <input type="text" ng-model="creditRequest.beneficiary_name" class="form-control" name="beneficiary_name" placeholder="BENEFICIARY NAME"  required>
                          <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.beneficiary_name.$invalid)" class="err-mark">Please enter a valid name.</p>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Account Number<font color="red"> * (Required)</font></small></label>
                          <input type="text" ng-model="creditRequest.account_number" class="form-control" name="account_number" placeholder="ENTER ACCOUNT NUMBER" required isnumber>
                          <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.account_number.$invalid || invalidAmount(creditRequest.account_number))" class="err-mark">Please enter a valid account number.</p>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Bank Name<font color="red"> * (Required)</font></small></label>
                          <input type="text" ng-model="creditRequest.bank_name" class="form-control" name="bank_name" placeholder="ENTER BANK NAME" required>
                          <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.bank_name.$invalid)" class="err-mark">Please enter a valid bank name.</p>
                        </div>
                      </div>

                      
                    </div>
                    <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>IFSC Code<font color="red"> * (Required)</font></small></label>
                          <!--input type="text" class="form-control" ng-model="creditRequest.remarks" name="remarks" placeholder="REMARKS"-->
                           <input type="text" class="form-control" ng-model="creditRequest.ifsc_code" name="ifsc_code" placeholder="IFSC CODE" required>
                        <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.ifsc_code.$invalid)" class="err-mark">Please enter a valid ifsc code.</p>
                        
                        </div>
                      </div>
                   <!--  <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Settlement Type</small></label>
                         
                          <div class="radio">
                            <input type="radio" ng-model="creditRequest.settlement_type" name="settlement_type" value="1" required>Auto Settlement

                          </div>
                          <div class="radio">
                            <input type="radio" ng-model="creditRequest.settlement_type" name="settlement_type" value="0" required>Manual Settlement
                            </div>
                        <p ng-show="creditRequestFrm.$submitted && (creditRequestFrm.settlement_type.$invalid)" class="err-mark">Please select a settlement type.</p>
                        </div>
                      </div> -->
                      
                    </div>
                    <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <button type="submit" class="btn btn-success" name="btn-credit-request"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;Submit</small></button>
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
  var settlement_details = {{json_encode($settlement_details->getItems()) }};
console.log(settlement_details);
</script>
<script>

//transactions.length > 0
  angular.module('DIPApp')
    .controller('CreditRequestCtrl', ['$scope', '$http', function ($scope, $http) {

      $scope.settlement_details = settlement_details.map(formatAdminTransaction)
  
      function formatAdminTransaction (tx) {        
        tx.created_at = new Date(tx.created_at)
        return tx
      }

      $scope.submit = submit
      $scope.invalidAmount = invalidAmount
      //console.log($scope.account_number)
      function submit (creditRequest) {
       req = Object.assign(creditRequest, {user_id: $scope.activeUserProfile.id})
       console.log(req)
        if ($scope.creditRequestFrm.$invalid || invalidAmount(creditRequest.account_number)) return
        req = Object.assign(creditRequest, {user_id: $scope.activeUserProfile.id})

        $http.post(`/post-settlement`, req)
        .then(data => {
          oldToastr.success("Details added successfully.")
          // sweetAlert('Success', 'Credit sent to the agent.', 'success')
          location.href = '/settlement'
        }, function (err) {
          //console.log(err.data)
          if (err.data.code && err.data.code == 1) {
            sweetAlert('Error', 'Missing data. Fill all the details please.', 'error')
            return
          }
          if (err.data.code && err.data.code == 2) {
            sweetAlert('Error', 'Insufficient Balance.', 'error')
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
        return amount >= 100 && amount <= 9999999999999999 ? false : true
      }

      function fail (err) {
        console.log(err)
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
