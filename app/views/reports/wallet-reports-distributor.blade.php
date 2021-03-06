<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')

<div ng-controller="reportsCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">

			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">Wallet Reports<span class="panel-subtitle">Snapshot</span></div>
				
				<div class="panel-body">
                    <div class="row">
					{{Form::open(array("url"=>"export"))}}
									<div class='col-md-5'>
										<div class="form-group">
											<div class='input-group date' id='datetimepicker6'>
												<input type='text' class="form-control" placeholder="From Date" name="from_date" required=""/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
									<div class='col-md-5'>
										<div class="form-group">
											<div class='input-group date' id='datetimepicker7'>
												<input type='text' class="form-control" placeholder="To Date" name="to_date" required=""/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
									<div class='col-md-2'>
										<div class="form-group">
											<button type="submit" class="btn btn-primary">Export</button>
										</div>
									</div>
									{{Form::close()}}
				</div>
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default panel-table">
								<!--div class="panel-heading">
									<!-- <div class="tools"><span class="icon mdi mdi-more-vert"></span></div> -->
									<!--div class="title">Wallet Transaction</div>
									<button ng-click="" class="btn btn-primary">Export as excel</button>
								</div-->
								
								<div class="panel-heading">
									<!-- <div class="tools"><span class="icon mdi mdi-more-vert"></span></div> -->
									<div class="title row">
										<div class="col-sm-3">
											<input class="form-control input-sm" type="text" placeholder="Search" ng-model="search">
										</div>
										<div class="clearfix"></div>
									</div>

								</div>
								<div class="panel-body table-responsive">
									<table class="table table-striped table-bordered">
											<thead>
												<tr>
													<th>Transaction Date</th>
													<th>Activity</th>
													<th>Narration</th>
													<th>Opening Balance</th>
													<th>Credit</th>
													<th>Debit</th>
													<th>Closing Balance</th>
												</tr>
											</thead>
											<tbody class="no-border-x">
												<tr ng-repeat="wallet in wallets |  filter:search"">
													<td>@{{wallet.created_at | date: 'medium'}}</td>
												<td>@{{wallet.activity}}</td>
												<td>@{{wallet.narration}}</td>
												<td ng-if="wallet.transaction_type == 1 || wallet.transaction_type == 7">@{{wallet.balance - wallet.amount | currency: 'Rs. '}}</td>
												<td ng-if="wallet.transaction_type == 0">@{{ (wallet.balance * 1) + (wallet.amount * 1) | currency: 'Rs. ' }}</td>
												<td ng-if="wallet.transaction_type == 1 || wallet.transaction_type == 7">
													<span class="label label-success">
														@{{wallet.amount | currency: 'Rs. '}}
													</span>
												</td>
												<td>0</td>
												<td ng-if="wallet.transaction_type == 0">
													<span class="label label-danger">@{{wallet.amount | currency: 'Rs. '}}
													</span>
												</td>
												<td>@{{wallet.balance | currency: 'Rs. '}}</td>
											</tr>
											<tr ng-hide="wallets.length > 0">
												<td colspan="7">No wallet transactions conducted yet.</td>
											</tr>
										</tbody>
									</table>
										<?php Paginator::setPageName('page'); ?>
										{{ $walletsObj->appends(getAppendData())->links() }}
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
					</div>
				</div>
			</div>
		</div>
	</div>
	@stop
	@section('javascript')


	<script>
		var wallets = {{ json_encode($wallets) }}
	</script>
	<script>
		angular.module('DIPApp')
		.controller('reportsCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
			window.s = $scope
			$scope.wallets = wallets

			$scope.wallets = wallets.map(formatAdminTransaction)
			var statusDict = {0: 'Pending', 1: 'Approved', 2: 'Rejected'}


			function formatAdminTransaction (tx) {

				tx.created_at = new Date(tx.created_at)
				return tx
			}

			function fail (err) {

				sweetAlert('Error', 'Something went wrong', 'error')
			}
		}])
	</script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>   



	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
	<script type="text/javascript">
		$(function () {
			$('#datetimepicker6').datetimepicker({ format: 'DD-MM-YYYY',maxDate:new Date()});
			$('#datetimepicker7').datetimepicker({
				format: 'DD-MM-YYYY',

				useCurrent: false 
			});
			$("#datetimepicker6").on("dp.change", function (e) { 

				$('#datetimepicker7').data("DateTimePicker");
			});
			$("#datetimepicker7").on("dp.change", function (e) {

				var from_date = moment($("input[name=from_date]").val(),"DD-MM-YYYY");
				var to_date = moment($("input[name=to_date]").val(),"DD-MM-YYYY"); 

				var days_difference = to_date.diff(from_date, 'days');
				if(days_difference >30)
				{

					sweetAlert('Error', 'Date diffrence should be eqaual to 30 or less than 30', 'error');

					$("input[name=to_date]").val("");
				}

			});
		});
	</script>
	@stop