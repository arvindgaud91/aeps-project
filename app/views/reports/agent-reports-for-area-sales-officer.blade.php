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
            @if( Auth::user()->isRegionalHead() )
            <li class="active"><a>Regional Head Sales Reports</a></li>
            @endif
            @if( Auth::user()->isStateHead() )
            <li class="active"><a>State Head Sales Reports</a></li>
            @endif
            @if( Auth::user()->isClusterHead() )
            <li class="active"><a>Cluster Head Sales Reports</a></li>
            @endif
            @if( Auth::user()->isAreaSalesManager() )
            <li class="active"><a>Area Sales Manager Sales Reports</a></li>
            @endif
            @if( Auth::user()->isAreaSalesOfficer() )
            <li class="active"><a>Area Sales Officer Sales Reports</a></li>
            @endif
          </ul>
        </div>
      </div>
      <div class="container">
        <div class='col-md-4'>      
        </div>
        <div class='col-md-5'>     
        </div>
        <div class='col-md-2'>
            <a ng-href="/export-agent-sales-report-for-distributors/@{{parentId}}" class="btn btn-primary">Export as excel</a> 
        </div>
      </div>
         <ul class="nav nav-pills nav-justified">
            @if( Auth::user()->isRegionalHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/regional-head-report">State Head Sales Reports</a></li>
              <li class="active"><a>Agent Sales Reports</a></li>
              <li><a href="/state-head-regional-head-date-report">State Head Sales Reports Date Wise</a></li>
            </ul>
            @endif
            @if( Auth::user()->isStateHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/state-head-report">Cluster Head Sales Reports</a></li>
              <li class="active"><a>Agent Sales Reports</a></li>
              <li><a href="/cluster-head-state-head-date-report">Cluster Head Sales Reports Date Wise</a></li>
            </ul>
            @endif
           @if( Auth::user()->isClusterHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/cluster-head-report">Area Sales Manager Sales Reports</a></li>
              <li class="active"><a>Agent Sales Reports</a></li>
              <li><a href="/area-sales-manager-cluster-head-date-report">Area Sales Manager Sales Reports Date Wise</a></li>
            </ul>
            @endif
           @if( Auth::user()->isAreaSalesManager() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/area-sales-manager-report">Area Sales Officer Sales Reports</a></li>
              <li class="active"><a>Agent Sales Reports</a></li>
              <li><a href="/area-sales-officer-area-sales-manager-date-report">Area Sales Officer Sales Reports Date Wise</a></li>
            </ul>
            @endif
            @if( Auth::user()->isAreaSalesOfficer() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/area-sales-officer-report">Sales Executive Sales Reports</a></li>
            <li class="active"><a>Agent Sales Reports</a></li>
            <li><a href="/sales-executive-area-sales-officer-date-report">Sales Executive Sales Reports Date Wise</a></li>
            </ul>
            @endif
            
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
                          <th>Total Amount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="agentSale in agentSales">
                        <td>@{{agentSale.id}}</td>
                        <td>@{{agentSale.name}}</td>
                        <td>@{{agentSale.csr_id}}</td>
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
                   <h4 ng-show="distributorSales.length == 0">No Agent Sales Report</h4>
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
  var parentId = {{json_encode($parentId)}}

</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope

  console.log($http);

  $scope.agentSales = agentSales
  $scope.agentSum = agentSum
  $scope.parentId = parentId
  console.log($scope.agentSales)
  console.log($scope.agentSum)
  console.log($scope.parentId)
 
  function fail (err) {
    console.log(err)
    sweetAlert('Error', 'Something went wrong', 'error')
  }
}])
</script>
@stop


