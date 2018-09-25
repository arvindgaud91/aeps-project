<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="reportsCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">Transaction Reports <span class="panel-subtitle">Snapshot</span></div>
				<br>
				<div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="hpanel">
                                <div class="panel-heading">
                                    <div class="panel-tools">
                                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                                        <a class="closebox"><i class="fa fa-times"></i></a>
                                    </div>
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
                                    <div class="panel-tools">
                                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                                        <a class="closebox"><i class="fa fa-times"></i></a>
                                    </div>
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
                                    <div class="panel-tools">
                                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                                        <a class="closebox"><i class="fa fa-times"></i></a>
                                    </div>
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
						<div class="col-md-12">
							<button ng-click="exportFile('aeps-transactions', transactions)" class="btn btn-primary" style="float: right;">Export as excel</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="panel-default panel-table">
								<!-- <div class="panel-heading">
									 <div class="tools"><span class="icon mdi mdi-more-vert"></span></div> 
									 <div class="title">Last Five Transactions</div>
								</div> -->
								<div class="panel-body table-responsive">
									<table class="datatable mdl-data-table dataTable table table-striped table-borderless" ng-show="transactions.length>0">
										<thead>
									        <tr>
									            <th>ID</th>
									            <th>First Name</th>
									            <th>Last Name</th>
									            <th>Email</th>
									            <th>Gender</th>
									            <th>Country</th>
									            <th>Salary</th>
									        </tr>
									    </thead>
										<!-- <tbody class="no-border-x">
											<tr ng-repeat="transaction in transactions">
												<td>@{{transaction.tx_id}}</td>
												<td>@{{transaction.tx_date | date: 'medium'}}</td>
												<td>@{{transaction.service}}</td>
												<td>@{{transaction.aadhar_no}}</td>
												<td>@{{transaction.bank_name}}</td>
												<td>@{{transaction.rrn_no}}</td>
												<td>@{{transaction.amount}}</td>
												<td ng-hide ="<?php echo Auth::user()->vendor->master_wallet_id; ?> == 1">@{{transaction.commission_amount}}</td>
												<td ng-hide ="<?php echo Auth::user()->vendor->master_wallet_id; ?> == 1">@{{transaction.balance}}</td>
												<td ng-hide ="<?php echo Auth::user()->vendor->master_wallet_id; ?> == 1">@{{transaction.wallet_balance}}</td>
												<td>@{{transaction.status}}</td>
												<td>@{{transaction.remarks}}</td>
												<td><a class="btn btn-primary" type="submit" ng-href="@{{transaction.receipt_link}}">Receipt</a></td>
											</tr>
										</tbody> -->
									</table>
									{{ $txObjs->appends(getAppendData())->links() }}
									<?php 
										function getAppendData ()
										{
											$append = [];
											if (Input::has('fromDate') && Input::has('toDate')) {
												$append['fromDate'] = Input::get('fromDate', '');
												$append['toDate'] = Input::get('toDate', '');
											}
											return $append;
										}
									?>
									<h5 ng-hide="transactions.length > 0">No transactions conducted yet. Click on AEPS to begin your first transaction.</h5>
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
<script>
	var transactions = {{ $transactions }}

</script>
<script>
angular.module('DIPApp')
.controller('reportsCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
	$scope.transactions = transactions.map(formatTransaction)

	$scope.exportFile = exportFile
    
    $scope.balEnqUnsuccess = {{$balEnqUnsuccess}}
    $scope.balEnqSuccess = {{$balEnqSuccess}}
    $scope.depositUnsuccess = {{$depositUnsuccess}}
    $scope.depositSuccess = {{$depositSuccess}}
    $scope.withdrawUnsuccess = {{$withdrawUnsuccess}}
    $scope.withdrawSuccess = {{$withdrawSuccess}}
	
	function formatTransaction (tx) {
		tx.tx_date = new Date(tx.tx_date.date)
		return tx
	}

		function exportFile (filename, data) {
		  $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
	  	
	    	newObj = {
	    		'Transaction_ID': obj.tx_id,
	    		'Transaction_Date': $filter('date')(obj.tx_date, 'medium'),
	    		'Service': obj.service ,
	    		'Aadhaar_No': obj.aadhar_no ,
	    		'Bank': obj.bank_name,
	    		'RRN Number': obj.rrn_no,
	    		'Amount': obj.amount,
	    		'Commission_Amount': obj.commission_amount,
	    		'Bank_Balance': obj.balance,
	    		'Wallet_Balance': obj.wallet_balance,
	    		'Status': obj.status,
	    		'Remark': obj.remarks
	    	}
		    
		    return newObj
		  })}).then(function (data) {
		    window.location.href = '/exports/'+data.data+'.xls'
		  }, console.log)
		}


	function fail (err) {
		
		sweetAlert('Error', 'Something went wrong', 'error')
	}
                            
        c3.generate({
            bindto: '#deposit-chart',
            data:{
                columns: [
                    ['success', $scope.depositSuccess],
                    ['unsuccess', $scope.depositUnsuccess]
                ],
                colors:{
                    success: '#62cb31',
                    unsuccess: '#FF0000'
                },
                type : 'pie'
            }
        });

        c3.generate({
            bindto: '#withdraw-chart',
            data:{
                columns: [
                    ['success', $scope.withdrawSuccess],
                    ['unsuccess', $scope.withdrawUnsuccess]
                ],
                colors:{
                    success: '#62cb31',
                    unsuccess: '#FF0000'
                },
                type : 'pie'
            }
        });

        c3.generate({
            bindto: '#balance-enquiry-chart',
            data:{
                columns: [
                    ['success', $scope.balEnqSuccess],
                    ['unsuccess', $scope.balEnqUnsuccess]
                ],
                colors:{
                    success: '#62cb31',
                    unsuccess: '#FF0000'
                },
                type : 'pie'
            }
        }); 
}])
</script>
@stop