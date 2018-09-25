<?php use Acme\Auth\Auth;
  $user = Auth::user();
 ?>
@extends('layouts.master')
@section('content')
<div ng-controller="AgentsCtrl" class="head-weight">
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default panel-border-color panel-border-color-primary">
      <div class="panel-heading panel-heading-divider">My Agents<span class="panel-subtitle">List</span></div>
      <div class="panel-body">
        <form action="" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed">
          <div class="row">
            <div class="col-md-12">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th>NAME</th>
                    <th>CSR ID</th>
                    <th>EMAIL</th>
                    <th>MOBILE</th>
                    <th>JOINING DATE</th>
                    <th>BALANCE</th>
                    <th style="padding-left: 4%">ACTIONS</th>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="agent in agents">
                    <td>@{{agent.name}}</td>
                    <td>@{{agent.vendor_details.csr_id}}</td>
                    <td>@{{agent.email}}</td>
                    <td>@{{agent.phone_no}}</td>
                    <td>@{{agent.created_at | date:'MMM dd, yyyy'}}</td>
                    <td>@{{agent.vendor_details.balance | currency: 'Rs. '}}</td>


                      @if($user->vendor->master_wallet_id != 1)
                    <td>

                      @if($user->vendor->user_id == 2250 || $user->vendor->user_id == 4998 || $user->vendor->user_id == 4972 || $user->vendor->user_id == 711 || $user->vendor->user_id == 4974 )


                      @else
                     <button type="button" ng-click="credit(agent)" class="btn btn-success btn-xs"><i class="fa fa-plus"></i></button>
                      @endif
                      

                      @if($user->vendor->user_id ==1924 || $user->vendor->user_id ==1459 || $user->vendor->user_id ==2250 || $user->vendor->user_id ==38 || $user->vendor->user_id ==3141)
                      <button type="button" ng-click="debit(agent)" class="btn btn-danger btn-xs" style="margin-left: 5%;"><i class="fa fa-minus"></i></button> @endif
                    </td>
                    @endif
                  </tr>
                </tbody>
              </table>
              <h4 ng-show="agents.length == 0">No agents added yet</h4>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    var agents = {{ $agents }}
  </script>
</div>
@stop
@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('AgentsCtrl', ['$scope', '$http', function ($scope, $http) {
      //window.s = $scope
      $scope.agents = agents.map(formatAgent)
      $scope.credit = credit
      $scope.debit = debit

      function formatAgent (agent) {
        agent.created_at = new Date(agent.created_at)
        return agent
      }

      function credit (agent) {
        if ($scope.activeUserProfile.vendor_details.balance == 0)
          return sweetAlert('Error', 'Insufficient Balance.', 'error')
        location.href = `/users/actions/credit-request/vendor/${agent.id}`
      }
      function debit (agent) {
       location.href = `/users/actions/debit-request/vendor/${agent.id}`
      }
      function fail (err) {
       // console.log(err)
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
