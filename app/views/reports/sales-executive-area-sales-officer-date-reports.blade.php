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
              <button ng-click="exportFile('aeps-salesExecutiveSalesReportDateWise', salesExecutiveSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
          </div>
        </div>
        <br/>
          @if( Auth::user()->isRegionalHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/regional-head-report">State Head Sales Reports</a></li>
              <li><a href="/cluster-head-reports-for-regional-head">Cluster Head Sales Reports</a></li>
              <li class="active"><a>Sales Executive Sales Reports Date Wise</a></li>
            </ul>
            @endif
          @if( Auth::user()->isStateHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/state-head-report">Cluster Head Sales Reports</a></li>
              <li><a href="/area-sales-manager-reports-for-state-head">Area Sales Manager Sales Reports</a></li>
              <li class="active"><a>Sales Executive Sales Reports Date Wise</a></li>
            </ul>
            @endif
          @if( Auth::user()->isClusterHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/cluster-head-report">Area Sales Manager Sales Reports</a></li>
              <li><a href="/area-sales-officer-report-for-clustor-head">Area Sales Manager Sales Reports</a></li>
              <li class="active"><a>Sales Executive Sales Reports Date Wise</a></li>
            </ul>
            @endif
           @if( Auth::user()->isAreaSalesManager() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/area-sales-manager-report">Area Sales Officer Sales Reports</a></li>
              <li><a href="/sales-executive-area-sales-manager-report">Sales Executive Sales Reports</a></li>
              <li class="active"><a>Sales Executive Sales Reports Date Wise</a></li>
            </ul>
            @endif
            @if( Auth::user()->isAreaSalesOfficer() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/area-sales-officer-report">Sales Executive Sales Reports</a></li>
            <li><a href="distributor-area-sales-report">Distributor Sales Reports</a></li>
            <li class="active"><a>Sales Executive Sales Reports Date Wise</a></li>
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
                          <th width='15%'>Sales Executive Id</th>
                          <th width='20%'>Sales Executive Name</th>
                          <th width='10%'>Distributor Count</th>
                          <th width='15%'>FTDAmount</th>
                          <th width='15%'>LMTDAmount</th>
                          <th width='15%'>MTDAmount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="salesExecutiveSale in salesExecutiveSales">
                        <td>@{{salesExecutiveSale.id}}</td>
                        <td><a href="/distributor-date-report-for-area-sales-officer/@{{salesExecutiveSale.id}}">@{{salesExecutiveSale.name}}</a></td>
                        <td>@{{countOfDistributor[salesExecutiveSale.id]}}</td>
                        <td>@{{salesExecutiveFTDSum[salesExecutiveSale.id]}}</td>
                        <td>@{{salesExecutiveLMTDSum[salesExecutiveSale.id]}}</td>
                        <td>@{{salesExecutiveMTDSum[salesExecutiveSale.id]}}</td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <?php Paginator::setPageName('page'); ?>
                  {{ $salesExecutiveSalesObj->appends(getAppendData())->links() }}
                  <?php
                    function getAppendData ()
                    {
                      return [];
                    }
                  ?>
                   <h4 ng-show="salesExecutiveSales.length == 0">No Sales Executive Sales Report</h4>
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

  var salesExecutiveSales = {{json_encode($salesExecutiveSales) }}
  var countOfDistributor ={{json_encode($countOfDistributor)}}
  var salesExecutiveFTDSum ={{json_encode($salesExecutiveFTDSum)}}
  var salesExecutiveLMTDSum ={{json_encode($salesExecutiveLMTDSum)}}
  var salesExecutiveMTDSum = {{json_encode($salesExecutiveMTDSum)}}
</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope
  $scope.salesExecutiveSales = salesExecutiveSales
  $scope.countOfDistributor=countOfDistributor
  $scope.salesExecutiveFTDSum = salesExecutiveFTDSum
  $scope.salesExecutiveLMTDSum = salesExecutiveLMTDSum
  $scope.salesExecutiveMTDSum = salesExecutiveMTDSum
  console.log($scope.salesExecutiveSales)
  console.log($scope.countOfDistributor)
  console.log($scope.salesExecutiveFTDSum)
  console.log($scope.salesExecutiveLMTDSum)
  console.log($scope.salesExecutiveMTDSum)

  $scope.exportFile = exportFile

  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Sales Executive Id': obj.id,
        'Sales Executive Name': obj.name,
        'Distributor Count': countOfDistributor[obj.id],
        'FTDAmount': salesExecutiveFTDSum[obj.id],
        'LMTDAmount': salesExecutiveLMTDSum[obj.id],
        'MTDAmount': salesExecutiveMTDSum[obj.id]
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


