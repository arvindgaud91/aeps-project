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
            <li class="active"><a>Regional Head Reports</a></li>
          </ul>
        </div>
        </div>
        <div class="container">
        <div class='col-md-4'>      
        </div>
        <div class='col-md-5'>     
        </div>
        <div class='col-md-2'>
            <a ng-href="/export-distributor-sales-report" class="btn btn-primary">Export as excel</a> 
        </div>
      </div>
         <ul class="nav nav-pills nav-justified">
            <li class="active"><a>State Head Sales Reports</a></li>
            <li><a href="/agent-sales-executive-report">Agent Sales Reports</a></li>
            <li><a href="/distributor-sales-executive-date-report">State Head Sales Reports Date Wise</a></li>
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
                          <th width='20%'>Distributor ID</th>
                          <th width='20%'>Distributor Name</th>
                          <th width='20%'>Mobile No</th>
                          <th width='20%'>Agent Count</th>
                          <th width='20%'>Total Amount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="distributorSale in distributorSales">
                      <td>@{{distributorSale.id}}</td>
                        <td><a href="/agent-sales-executive-report-for-distributor/@{{distributorSale.id}}">@{{distributorSale.name}}</a></td>
                        <td>@{{distributorSale.phone_no}}</td>
                        <td>@{{distributorAgentCount[distributorSale.id]}}</td>
                        <td>@{{distributorAgentSum[distributorSale.id]}}</td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <?php Paginator::setPageName('page'); ?>
                  {{ $distributorSalesObj->appends(getAppendData())->links() }}
                  <?php
                    function getAppendData ()
                    {
                      return [];
                    }
                  ?>
                   <h4 ng-show="distributorSales.length == 0">No Distributor Sales Report</h4>
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

  var distributorSales = {{json_encode($distributorSales) }}
  var distributorAgentCount ={{json_encode($distributorAgentCount)}}
  var distributorAgentSum ={{json_encode($distributorAgentSum)}}

</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope
  $scope.distributorSales = distributorSales
  $scope.distributorAgentCount=distributorAgentCount
  $scope.distributorAgentSum=distributorAgentSum
  console.log($scope.distributorSales)
  console.log($scope.distributorAgentCount)
  console.log($scope.distributorAgentSum)

 
  function fail (err) {
    console.log(err)
    sweetAlert('Error', 'Something went wrong', 'error')
  }
}])
</script>
@stop


