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
        <div class="col-md-2">
            <button ng-click="exportFile('aeps-distributorSale', distributorSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
        </div>
      </div>
      <div class="clearfix"></div>
         <ul class="nav nav-pills nav-justified">
            @if( Auth::user()->isRegionalHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/regional-head-report">State Head Sales Reports</a></li>
              <li class="active"><a>Distributor Sales Reports</a></li>
              <li><a href="/state-head-regional-head-date-report">State Head Sales Reports Date Wise</a></li>
            </ul>
            @endif
            @if( Auth::user()->isStateHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/state-head-report">Cluster Head Sales Reports</a></li>
              <li class="active"><a>Distributor Sales Reports</a></li>
              <li><a href="/cluster-head-state-head-date-report">Cluster Head Sales Reports Date Wise</a></li>
            </ul>
            @endif
            @if( Auth::user()->isClusterHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/cluster-head-report">Area Sales Manager Sales Reports</a></li>
              <li class="active"><a>Distributor Sales Reports</a></li>
              <li><a href="/area-sales-manager-cluster-head-date-report">Area Sales Manager Sales Reports Date Wise</a></li>
            </ul>
            @endif
            @if( Auth::user()->isAreaSalesManager() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/area-sales-manager-report">Area Sales Officer Sales Reports</a></li>
              <li class="active"><a>Distributor Sales Reports</a></li>
              <li><a href="/area-sales-officer-area-sales-manager-date-report">Area Sales Officer Sales Reports Date Wise</a></li>
            </ul>
            @endif
            @if( Auth::user()->isAreaSalesOfficer() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/area-sales-officer-report">Sales Executive Sales Reports</a></li>
              <li class="active"><a>Distributor Sales Reports</a></li>
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
                          <th>Distributor Id</th>
                          <th>Distributor Name</th>
                          <th>Agent Count</th>
                          <th>Total Amount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="distributorSale in distributorSales">
                        <td>@{{distributorSale.id}}</td>
                        <td><a href="/agent-reports-for-area-sales-officer/@{{distributorSale.id}}">@{{distributorSale.name}}</a></td>
                        <td>@{{countOfAgent[distributorSale.id]}}</td>
                        <td>@{{agentSum[distributorSale.id]}}</td>
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
  var agentSum = {{json_encode($agentSum) }}
  var countOfAgent = {{json_encode($countOfAgent)}}

</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope
  $scope.distributorSales = distributorSales
  $scope.agentSum = agentSum
  $scope.countOfAgent = countOfAgent
  console.log($scope.distributorSales)
  console.log($scope.agentSum)
  console.log($scope.countOfAgent)

  $scope.exportFile = exportFile

  function exportFile (filename, data) {
      $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
        newObj = {
          'Distributor_ID': obj.id,
          'Distributor_Name': obj.name,
          'Agent_Count': countOfAgent[obj.id],
          'Total_Amount': agentSum[obj.id]
        }
        return newObj
      })}).then(function (data) {
        window.location.href = '/exports/'+data.data+'.xls'
      }, console.log)
    }
 
  function fail (err) {
    console.log(err)
    sweetAlert('Error', 'Something went wrong', 'error')
  }
}])
</script>
@stop


