<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="reportsCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">Transaction Reports<span class="panel-subtitle">Snapshot</span></div>
				<div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="hpanel">
                                <div class="panel-heading">
                                    DEPOSIT
                                </div>
                                <div class="panel-body">
                                    <div>
                                        <div id="deposit-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="hpanel">
                                <div class="panel-heading">
                                    WITHDRAW
                                </div>
                                <div class="panel-body">
                                    <div>
                                        <div id="withdraw-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="hpanel">
                                <div class="panel-heading">
                                    BALANCE ENQUIRY
                                </div>
                                <div class="panel-body">
                                    <div>
                                        <div id="balance-enquiry-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    	{{Form::open(array("url"=>"/export-transactions-report"))}}
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
								<!-- <div class="panel-heading">
									 <div class="tools"><span class="icon mdi mdi-more-vert"></span></div> 
									 <div class="title">Last Five Transactions</div>
									</div> -->
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
													<th>Bank Transaction Id</th>
													<th>Service</th>
													<th>Transaction Date</th>
													<th>User Name</th>
													<th>Mobile</th>
												<!--th>Beneficiary Name</th>
												<th>Beneficiary Account No</th-->
													<th>Total Amount</th>
													<th>Status</th>
													<th>Bank Remarks</th>
													<th ng-show="distributorType=='muthoot'">Muthoot Response</th>
												</tr>
											</thead>
											<tbody class="no-border-x">
												<tr ng-repeat="transaction in transactions |  filter:search"">
													<td>@{{transaction.id}}</td>
													<td>@{{transaction.type}}</td>
													<td>@{{transaction.created_at | date: 'medium'}}</td>
													<td>@{{transaction.name}}</td>
													<td>@{{transaction.phone_no}}</td>
												<!--td>@{{transaction.beneficiary.name}}</td>
												<td>@{{transaction.beneficiary.account_number}}</td-->
													<td>@{{transaction.amount | currency: 'Rs. '}}</td>
													<td>@{{transaction.status}}</td>
													<td>@{{transaction.remarks}}</td>
													<td ng-show="distributorType=='muthoot'">@{{transaction.response}}</td>
												</tr>
											</tbody>
										</table>
										<?php Paginator::setPageName('page'); ?>
										{{ $transactionsObj->appends(getAppendData())->links() }}
										<?php
										function getAppendData ()
										{
											return [];
										}
										?>
										<h5 ng-hide="transactions.length > 0">No transactions conducted yet.</h5>
									</div>
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
	<script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>
	<script>
		$('.input-daterange input').each(function(){
			$(this).datepicker('clearDates');
		});
		var transactions = {{json_encode($transactions) }}

	</script>
	<script>
		var distributorType = {{ json_encode($distributorType) }}

		angular.module('DIPApp')
		.controller('reportsCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
			window.s = $scope
			$scope.transactions = transactions
			var serviceDict = [
			'Balance request',
			'Deposit request',
			'Withdraw request',
			'To Pay request'
			];
            $scope.distributorType = distributorType

			$scope.transactions = transactions.map(formatAdminTransaction)
            
            $scope.balEnqUnsuccess = {{$balEnqUnsuccess}}
            $scope.balEnqSuccess = {{$balEnqSuccess}}
            $scope.depositUnsuccess = {{$depositUnsuccess}}
            $scope.depositSuccess = {{$depositSuccess}}
            $scope.withdrawUnsuccess = {{$withdrawUnsuccess}}
            $scope.withdrawSuccess = {{$withdrawSuccess}}

			function formatAdminTransaction (tx) {
				if ((tx.status == 3 || tx.status == 4 || tx.status == 0) && tx.result == 0) {
					tx.status='Failed'
				} 
				if ((tx.status == 3 || tx.status == 4) && tx.result == 1) {
					tx.status='Success'
				}
				tx.created_at = new Date(tx.created_at)
				tx.type=serviceDict[tx.type]
				var response_format = tx.response ? tx.response.split("--"):tx.response
				tx.response = tx.response ? response_format[1]: tx.response
				return tx
			}



			function fail (err) {
				console.log(err)
				sweetAlert('Error', 'Something went wrong', 'error')
			}
            
            c3.generate({
                bindto: '#deposit-chart',
                data:{
                    columns: [
                        ['success', $scope.depositSuccess],
                        ['failed', $scope.depositUnsuccess]
                    ],
                    colors:{
                        success: '#62cb31',
                        failed: '#FF0000'
                    },
                    type : 'pie'
                }
            });

            c3.generate({
                bindto: '#withdraw-chart',
                data:{
                    columns: [
                        ['success', $scope.withdrawSuccess],
                        ['failed', $scope.withdrawUnsuccess]
                    ],
                    colors:{
                        success: '#62cb31',
                        failed: '#FF0000'
                    },
                    type : 'pie'
                }
            });

            c3.generate({
                bindto: '#balance-enquiry-chart',
                data:{
                    columns: [
                        ['success', $scope.balEnqSuccess],
                        ['failed', $scope.balEnqUnsuccess]
                    ],
                    colors:{
                        success: '#62cb31',
                        failed: '#FF0000'
                    },
                    type : 'pie'
                }
            });  
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