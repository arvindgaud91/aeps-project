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
            <li class="active"><a>Area Sales Manager Reports</a></li>
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
              <button ng-click="exportFile('aeps-areaSalesManagerSale', areaSalesManagerSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
          </div>
        </div>
        @if( Auth::user()->isRegionalHead() )
          <ul class="nav nav-pills nav-justified">
            <li><a href="/regional-head-report">State Head Sales Reports</a></li>
            <li class="active"><a>Area Sales Manager Sales Reports</a></li>
            <li><a href="/state-head-regional-head-date-report">State Head Sales Reports Date Wise</a></li>
          </ul>
          @endif
        @if( Auth::user()->isStateHead() )
          <ul class="nav nav-pills nav-justified">
            <li><a href="/state-head-report">Cluster Head Sales Reports</a></li>
            <li class="active"><a>Area Sales Manager Sales Reports</a></li>
            <li><a href="/cluster-head-state-head-date-report">Cluster Head Sales Reports Date Wise</a></li>
          </ul>
          @endif
         @if( Auth::user()->isClusterHead() )
          <ul class="nav nav-pills nav-justified">
            <li><a href="/cluster-head-report">Area Sales Manager Sales Reports</a></li>
            <li class="active"><a>Area Sales Officer Sales Reports</a></li>
            <li><a href="/area-sales-manager-cluster-head-date-report">Area Sales Manager Sales Reports Date Wise</a></li>
          </ul>
          @endif
          @if( Auth::user()->isAreaSalesManager() )
          <ul class="nav nav-pills nav-justified">
            <li class="active"><a>Area Sales Officer Sales Reports</a></li>
            <li><a href="/sales-executive-area-sales-manager-report">Sales Executive Sales Reports</a></li>
            <li><a href="/area-sales-officer-area-sales-manager-date-report">Area Sales Officer Sales Reports Date Wise</a></li>
          </ul>
          @endif
         
        <div class="panel panel-default">
        
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="clearfix"></div>
              <div class="table-responsive">
                    <table id="sales" class="table table-striped table-borderless">
                      <thead>
                        <tr>
                          <th width='20%'>Area Sales Manager ID</th>
                          <th width='20%'>Area Sales Manager Name</th>
                          <th width='20%'>Area Sales Officer Count</th>
                          <th width='20%'>Total Amount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="areaSalesmanagerSale in areaSalesManagerSales">
                      <td>@{{areaSalesmanagerSale.id}}</td>
                        <td><a href="/area-sales-officer-for-clustor-head/@{{areaSalesmanagerSale.id}}">@{{areaSalesmanagerSale.name}}</a></td>
                        <td>@{{countOfAreaSalesOfficer[areaSalesmanagerSale.id]}}</td>
                        <td>@{{sumOfAgentAmount[areaSalesmanagerSale.id]}}</td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <?php Paginator::setPageName('page'); ?>
                  {{ $areaSalesManagerSalesObj->appends(getAppendData())->links() }}
                  <?php
                    function getAppendData ()
                    {
                      return [];
                    }
                  ?>
                   <h4 ng-show="areaSalesManagerSales.length == 0">No Area Sales Manager Sales Report</h4>
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
  var areaSalesManagerSales = {{json_encode($areaSalesManagerSales) }}
  var countOfAreaSalesOfficer = {{json_encode($countOfAreaSalesOfficer)}}
  var sumOfAgentAmount = {{json_encode($sumOfAgentAmount)}}
</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope

  
  $scope.areaSalesManagerSales = areaSalesManagerSales
  $scope.countOfAreaSalesOfficer = countOfAreaSalesOfficer
  $scope.sumOfAgentAmount = sumOfAgentAmount
  console.log($scope.areaSalesManagerSales)
  console.log($scope.countOfSalesExecutive)
  console.log($scope.sumOfAgentAmount)

  $scope.exportFile = exportFile

  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Area Sales Manager ID': obj.id,
        'Area Sales Manager Name': obj.name,
        'Area Sales Officer Count': countOfAreaSalesOfficer[obj.id],
        'Total Amount': sumOfAgentAmount[obj.id]
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


