<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')

<div ng-controller="SalesReportCtrl" id="page-wrapper">
  <div style="height: 40px;"></div>
  <div class="row">
    <div class="col-lg-12">
     
      <div class="panel panel-default">
        <div class="panel-body">
          <ul class="nav nav-pills nav-justified">
            <li class="active"><a>Sales Reports</a></li>
          </ul>
        </div>
      </div>
      <div class="container">
        <div class='col-md-4'>      
        </div>
        <div class='col-md-5'>     
        </div>
        <div class='col-md-2'>
            <a ng-href="/export-agent-sales-report" class="btn btn-primary">Export as excel</a> 
        </div>
      </div>
         <ul class="nav nav-pills nav-justified">
            <li><a href="/distributor-sales-executive-report">Distributor Sales Reports</a></li>
            <li class="active"><a>Agent Sales Reports</a></li>
            <li><a href="/distributor-sales-executive-date-report">Distributor Sales Reports Date Wise</a></li>
          </ul>
        <div class="panel panel-default">
        
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="clearfix"></div>
              <div class="table-responsive">
                    <table id="sales" class="table table-striped table-borderless">
                      <thead>
                        <tr>
                          <th>Agent Id</th>
                          <th>Agent Name</th>
                          <th>Code</th>
                          <!-- <th>Mobile No</th> -->
                          <th>Total Amount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="agentSale in agentSales">
                        <td>@{{agentSale.id}}</td>
                        <td>@{{agentSale.name}}</td>
                        <td>@{{agentSale.csr_id}}</td>
                        <!-- <td>@{{agentSale.phone_no}}</td> -->
                        <td>@{{agentSum[agentSale.id]}}</td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <?php Paginator::setPageName('page'); ?>
                  {{ $agentSalesObj->appends(getAppendData())->links() }}
                  <?php
                    function getAppendData ()
                    {
                      return [];
                    }
                  ?>
                   <h4 ng-show="agentSales.length == 0">No Agent Sales Report</h4>
                </div>
              </div>
            </div>
          </div>
          <!-- End Row -->
        </div>
      </div>
    </div>
  </div>
</div>
@stop
@section('javascript')
<script>

  var agentSales = {{json_encode($agentSales) }}
  var agentSum = {{json_encode($agentSum) }}


</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope
  $scope.agentSales = agentSales
  $scope.agentSum = agentSum
  console.log($scope.agentSales)
  console.log($scope.agentSum)
 
  function fail (err) {
    console.log(err)
    sweetAlert('Error', 'Something went wrong', 'error')
  }
}])
</script>
@stop


