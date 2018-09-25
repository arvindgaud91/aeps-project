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
          </ul>
        </div>
        </div>
        <div class="container">
          <div class='col-md-4'>      
          </div>
          <div class='col-md-5'>     
          </div>
          <div class="col-md-2">
            <button ng-click="exportFile('aeps-clusterHeadSalesDateReport', clusterHeadSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
          </div>
        </div>
        <br/>
            @if( Auth::user()->isRegionalHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/regional-head-report">State Head Sales Reports</a></li>
              <li><a href="/cluster-head-reports-for-regional-head">Cluster Head Sales Reports</a></li>
              <li class="active"><a>Cluster Head Sales Reports Date Wise</a></li>
            </ul>
            @endif
            @if( Auth::user()->isStateHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/state-head-report">Cluster Head Sales Reports</a></li>
              <li><a href="/area-sales-manager-reports-for-state-head">Area Sales Manager Sales Reports</a></li>
              <li class="active"><a>Cluster Head Sales Reports Date Wise</a></li>
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
                          <th width='15%'>Cluster Head ID</th>
                          <th width='20%'>Cluster Head Name</th>
                          <th width='10%'>Area Sales Manager Count</th>
                          <th width='15%'>FTDAmount</th>
                          <th width='15%'>LMTDAmount</th>
                          <th width='15%'>MTDAmount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="clusterHeadSale in clusterHeadSales">
                        <td>@{{clusterHeadSale.id}}</td>
                        <td><a href="/area-sales-manager-date-reports-for-cluster-head/@{{clusterHeadSale.id}}">@{{clusterHeadSale.name}}</a></td>
                        <td>@{{countOfSalesManager[clusterHeadSale.id]}}</td>
                        <td>@{{clusterHeadFTDSum[clusterHeadSale.id]}}</td>
                        <td>@{{clusterHeadLMTDSum[clusterHeadSale.id]}}</td>
                        <td>@{{clusterHeadMTDSum[clusterHeadSale.id]}}</td>
                      </tr>
                    </tbody>
                  </table>
                  </div>
                  <?php Paginator::setPageName('page'); ?>
                  {{ $clusterHeadSalesObj->appends(getAppendData())->links() }}
                  <?php
                    function getAppendData ()
                    {
                      return [];
                    }
                  ?>
                   <h4 ng-show="clusterHeadSales.length == 0">No Cluster Head Sales Report</h4>
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

  var clusterHeadSales = {{json_encode($clusterHeadSales) }}
  var countOfSalesManager ={{json_encode($countOfSalesManager)}}
  var clusterHeadFTDSum ={{json_encode($clusterHeadFTDSum)}}
  var clusterHeadLMTDSum ={{json_encode($clusterHeadLMTDSum)}}
  var clusterHeadMTDSum = {{json_encode($clusterHeadMTDSum)}}
</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope
  $scope.clusterHeadSales = clusterHeadSales
  $scope.countOfSalesManager=countOfSalesManager
  $scope.clusterHeadFTDSum = clusterHeadFTDSum
  $scope.clusterHeadLMTDSum = clusterHeadLMTDSum
  $scope.clusterHeadMTDSum = clusterHeadMTDSum
  console.log($scope.clusterHeadSales)
  console.log($scope.countOfSalesManager)
  console.log($scope.clusterHeadFTDSum) 
  console.log($scope.clusterHeadLMTDSum)
  console.log($scope.clusterHeadMTDSum)

  $scope.exportFile = exportFile

  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Cluster Head ID': obj.id,
        'Cluster Head Name': obj.name,
        'Area Sales Manager Count': countOfSalesManager[obj.id],
        'FTDAmount': clusterHeadFTDSum[obj.id],
        'LMTDAmount': clusterHeadLMTDSum[obj.id],
        'MTDAmount': clusterHeadMTDSum[obj.id]
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


