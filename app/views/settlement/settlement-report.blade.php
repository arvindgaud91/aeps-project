<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<style>
	.label-default {
    background-color: #777;
     border-radius: 2px;
}
.label {
    font-size: 11px;
    border-radius: .25em;
}
</style>
<div ng-controller="reportsCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary m-b-0">
				<div class="panel-heading panel-heading-divider">Settlement Reports</div>
				<div class="panel-body">
					<div class="row">
						
						<div class="col-md-12">
							<div class="panel panel-default panel-table m-b-0">
								<div class="panel-heading">
									<!-- <div class="tools"><span class="icon mdi mdi-more-vert"></span></div> -->
                                    <div class="row">
                                        <div class="col-md-1" style="padding-top:5px;">Search</div>
                                        <div class="col-md-3">
                                            <input class="form-control input-sm" type="text" ng-model="search">
                                        </div>
                                    </div>
								</div>
								<div class="panel-body table-responsive">
                                                                    
									<!-- <table ng-show="transactions.length > 0" class="table table-striped table-borderless"> -->
									<table class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>Request Id</th>
												<th>Amount</th>
												<th>Beneficiary Name</th>
												<th>Account Number</th>
												<th>Status</th>
                                                <th>Date</th>
											</tr>
										</thead>
										<tbody class="no-border-x">
											<tr ng-repeat="tx in settlement_data |  filter:search">
												<td>@{{tx.id}}</td>
												<td>@{{tx.amount }}</td>
												<td>@{{tx.beneficiary_name}}</td>
												<td>@{{tx.account_number}}</td>
												<td ng-switch="tx.status">
													<span ng-switch-when="Rejected" class="label label-danger">
														@{{tx.status}}
													</span>
													<span ng-switch-when="Disbursed" class="label label-success">
														@{{tx.status}}
													</span>
													<span ng-switch-when="Inprogress" class="label label-warning">
														@{{tx.status}}
													</span>
													<span ng-switch-when="Pending" class="label label-default" style="background-color: white;">
														@{{tx.status}}
													</span>
												</td>
												<td>@{{tx.created_at | date:"dd/MM/yyyy 'at' h:mma" }}</td>
                                                
												
											</tr>
										</tbody>
									</table>
									<?php Paginator::setPageName('page'); ?>
									{{ $settlement_data->appends(getAppendData())->links() }}
									<!-- <h5 ng-hide="transactions.length > 0">No transactions conducted yet. Click on AEPS to begin your first transaction.</h5> -->
									<?php
										function getAppendData ()
										{
											return [];
										}
									?>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="col-xs-12 col-md-12">
						<div class="widget widget-calendar">
							<div id="calendar-widget"></div>
						</div>
					</div> -->
					<!-- End Row -->
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@section('javascript')
<script>
	var settlement_data = {{ json_encode($settlement_data->getItems()) }};
	//var statusDict = ['Pending','Inprogress','Disbursed'];
	console.log(settlement_data);
</script>
<script>
angular.module('DIPApp')
.controller('reportsCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
	window.s = $scope
	
	$scope.settlement_data = settlement_data.map(formatAdminTransaction)
	//$scope.statusDict = statusDict
	
	
	function formatAdminTransaction (tx) {
                
                //tx.status = statusDict[tx.status]
		tx.created_at = new Date(tx.created_at)
		tx.response = tx.response!=""? tx.response: "--"
                tx.response_date = tx.response_date!=""? new Date(tx.response_date) : "--"
                
		
		return tx
	}
	
	function fail (err) {
		console.log(err);
		sweetAlert('Error', 'Something went wrong', 'error');
	}
}])
</script>
@stop