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
            @if( Auth::user()->isSalesExecutive() )
            <li class="active"><a>Sales Executive Reports</a></li>
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
            <button ng-click="exportFile('aeps-agentSalesReportDateWise', agentSales)" class="btn btn-primary" style="float: right;">Export as excel</button>
          </div>
        </div>
        <br/>
          @if( Auth::user()->isRegionalHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/regional-head-report">State Head Sales Reports</a></li>
              <li><a href="/cluster-head-reports-for-regional-head">Cluster Head Sales Reports</a></li>
              <li class="active"><a>Agent Reports Date Wise</a></li>
            </ul>
          @endif
          @if( Auth::user()->isStateHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/state-head-report">Cluster Head Sales Reports</a></li>
              <li><a href="/area-sales-manager-reports-for-state-head">Area Sales Manager Sales Reports</a></li>
              <li class="active"><a>Agent Reports Date Wise</a></li>
            </ul>
            @endif
          @if( Auth::user()->isClusterHead() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/cluster-head-report">Area Sales Manager Sales Reports</a></li>
              <li><a href="/area-sales-officer-report-for-clustor-head">Area Sales Officer Sales Reports</a></li>
              <li class="active"><a>Agent Reports Date Wise</a></li>
            </ul>
            @endif
          @if( Auth::user()->isAreaSalesManager() )
            <ul class="nav nav-pills nav-justified">
              <li><a href="/area-sales-manager-report">Area Sales Officer Sales Reports</a></li>
              <li><a href="/sales-executive-area-sales-manager-report">Sales Executive Sales Reports</a></li>
              <li class="active"><a>Agent Reports Date Wise</a></li>
            </ul>
            @endif
            @if( Auth::user()->isAreaSalesOfficer() )
            <ul class="nav nav-pills nav-justified">
            <li><a href="/area-sales-officer-report">Sales Executive Sales Reports</a></li>
            <li><a href="/distributor-area-sales-report">Distributor Sales Reports</a></li>
            <li class="active"><a>Agent Reports Date Wise</a></li>
            </ul>
            @endif
            @if( Auth::user()->isSalesExecutive() )
            <ul class="nav nav-pills nav-justified">
            <li><a href="/distributor-sales-executive-report">Sales Executive Sales Reports</a></li>
            <li><a href="/agent-sales-executive-report">Distributor Sales Reports</a></li>
            <li class="active"><a>Agent Reports Date Wise</a></li>
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
                          <th width='15%'>Agent Id</th>
                          <th width='20%'>Agent Name</th>
                          <th width='10%'>Code</th>
                          <th width='15%'>FTDAmount</th>
                          <th width='15%'>LMTDAmount</th>
                          <th width='15%'>MTDAmount</th>
                        </tr>
                      </thead>
                    <tbody class="no-border-x">
                      <tr ng-repeat="agentSale in agentSales">
                        <td>@{{agentSale.id}}</td>
                        <td>@{{agentSale.name}}</td>
                        <td>@{{agentSale.csr_id}}</td>
                        <td>@{{agentFTDSum[agentSale.id]}}</td>
                        <td>@{{agentLMTDSum[agentSale.id]}}</td>
                        <td>@{{agentMTDSum[agentSale.id]}}</td>
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
  var agentFTDSum ={{json_encode($agentFTDSum)}}
  var agentLMTDSum ={{json_encode($agentLMTDSum)}}
  var agentMTDSum ={{json_encode($agentMTDSum)}}
</script>
<script>
angular.module('DIPApp')
.controller('SalesReportCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
  window.s = $scope
  $scope.agentSales = agentSales
  $scope.agentFTDSum=agentFTDSum
  $scope.agentLMTDSum=agentLMTDSum
  $scope.agentMTDSum=agentMTDSum
  console.log($scope.agentSales)
  console.log($scope.agentFTDSum)
  console.log($scope.agentLMTDSum)
  console.log($scope.agentMTDSum)
  $scope.exportFile = exportFile

  function exportFile (filename, data) {
    $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
      newObj = {
        'Agent Id': obj.id,
        'Agent Name': obj.name,
        'Code': obj.csr_id,
        'FTDAmount': agentFTDSum[obj.id],
        'LMTDAmount': agentLMTDSum[obj.id],
        'MTDAmount': agentMTDSum[obj.id]
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
