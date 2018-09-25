<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="reportsCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">State Head Reports<span class="panel-subtitle">Snapshot</span></div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default panel-table">
								<!-- <div class="panel-heading">
									<div class="tools"><span class="icon mdi mdi-more-vert"></span></div>
									<div class="title">Last Five Transactions</div>
								</div> -->
								@if(  Auth::user()->isDistributor())
								<div class="panel-body table-responsive">
									<table class="table table-striped table-borderless" >
										<thead>
											<tr>
												<th>Transaction ID</th>
												<th>Transaction Date</th>
												<th>Service</th>
												<th>Agent Name</th>
												<th>Amount</th>
												<th>Commission Amount</th>
											</tr>
										</thead>
										<tbody class="no-border-x">
											<tr>
												<td>Transaction ID</td>
												<td>Transaction Date</td>
												<td>Service</td>
												<td>Agent Name</td>
												<td>Amount</td>
												<td>Commission  Amount</td>
											</tr>
										</tbody>
									</table>
									<h5>No transactions conducted yet. Click on AEPS to begin your first transaction.</h5>
								</div>
								@endif
								@if(  Auth::user()->isClusterHead())
								<div class="panel-body table-responsive">
									<table class="table table-striped table-borderless">
										<thead>
											<tr>
												<th>Transaction ID</th>
												<th>Transaction Date</th>
												<th>Service</th>
												<th>Agent Name</th>
												<th>Distributor Name</th>
												<th>Amount</th>
												<th>Commission Amount</th>
											</tr>
										</thead>
										<tbody class="no-border-x">
											<tr>
												<td>Transaction ID</td>
												<td>Transaction Date</td>
												<td>Service</td>
												<td>Agent Name</td>
												<td>Distributor Name</td>
												<td>Amount</td>
												<td>Commission  Amount</td>
											</tr>
										</tbody>
									</table>
									<h5>No transactions conducted yet. Click on AEPS to begin your first transaction.</h5>
								</div>
								@endif
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-12">
						<div class="widget widget-calendar">
							<div id="calendar-widget"></div>
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
	
</script>
<script>
angular.module('DIPApp')
.controller('reportsCtrl', ['$scope', '$http', function ($scope, $http) {
	//window.s = $scope;
	
	function fail (err) {
		//console.log(err);
		sweetAlert('Error', 'Something went wrong', 'error');
	}
}])
</script>
@stop
