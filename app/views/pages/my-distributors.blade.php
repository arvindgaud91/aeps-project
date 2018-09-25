<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="MyDistributorsCtrl" class="head-weight">
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default panel-border-color panel-border-color-primary">
      <div class="panel-heading panel-heading-divider">My Distributors<span class="panel-subtitle">List</span></div>
      <div class="panel-body">
        <form action="" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed">
          <div class="row">
            <div class="col-md-12">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>MOBILE</th>
                    <th>JOINING DATE</th>
                    <th>BALANCE</th>
                    <th style="padding-left: 4%">ACTIONS</th>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="distributor in distributors">
                    <td>@{{distributor.name}}</td>
                    <td>@{{distributor.email}}</td>
                    <td>@{{distributor.phone_no}}</td>
                    <td>@{{distributor.created_at | date:'MMM dd, yyyy'}}</td>
                    <td>@{{distributor.vendor_details.balance | currency: 'Rs. '}}</td>
                    <td>
                      <!-- <a ng-href="/distributors/@{{distributor.id}}/agents" class="btn btn-primary">Agents</a> -->
                      <!--<button type="button" ng-click="credit(distributor)" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i>&nbsp;&nbsp;Credit</button> Old code -->
                      <!--<button type="button" ng-click="debit(distributor)" class="btn btn-primary btn-xs" style="margin-left: 5%;"><i class="fa fa-minus" ></i>&nbsp;&nbsp;Debit</button> Old code -->
                        
                        <button type="button" ng-click="credit(distributor)" class="btn btn-success btn-xs">
                            <i class="fa fa-plus"></i>
                        </button>
                        <button type="button" ng-click="debit(distributor)" class="btn btn-danger btn-xs" style="margin-left: 5%;">
                            <i class="fa fa-minus"></i>
                        </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <h4 ng-show="distributors.length == 0">No distributors added yet</h4>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    var distributors = {{ $distributors }};
  </script>
</div>
@stop
@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('MyDistributorsCtrl', ['$scope', '$http', function ($scope, $http) {
      //window.s = $scope
      $scope.distributors = distributors.map(formatDistributor)
      $scope.credit = credit
      $scope.debit = debit
    

      function formatDistributor (dist) {
        dist.created_at = new Date(dist.created_at)
        return dist
      }

      function credit (dist) {
        if ($scope.activeUserProfile.vendor_details.balance == 0)
          return sweetAlert('Error', 'Insufficient Balance.', 'error')
        location.href = `/users/actions/credit-request/vendor/${dist.id}`
      }
      function debit (dist) {
       location.href = `/users/actions/debit-request/vendor/${dist.id}`
      }
      function fail (err) {
        //console.log(err)
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
