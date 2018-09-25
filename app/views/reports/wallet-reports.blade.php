<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="reportsCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="hpanel">
				<div class="panel-heading panel-heading-divider">Wallet Reports </div>
				<div class="panel-body">
                    <div class="row">
                    {{Form::open(array("url"=>"agent-wallet-vendor-sheet"))}}
                    <div class='col-md-5'>
                        <div class="form-group">
                            <div class='input-group date' id='datetimepicker8'>
                                <input type='text' class="form-control" placeholder="From Date" name="from_date2" required=""/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-5'>
                        <div class="form-group">
                            <div class='input-group date' id='datetimepicker9'>
                                <input type='text' class="form-control" placeholder="To Date" name="to_date2" required=""/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-2'>
                        <div class="form-group">
                            <button ng-click="exportFile('vendor-transactions', vendorTransactions)" class="btn btn-primary" style="float: right;"><i class="fa fa-file-excel-o"></i> Export as excel</button>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default panel-table">
								<div class="panel-heading">
									<!-- <div class="tools"><span class="icon mdi mdi-more-vert"></span></div> -->
									<div class="title">Wallet Transaction</div>
									<!--<br>
									<button ng-click="exportFile('vendor-transactions', vendorTransactions)" class="btn btn-primary" style="float: right;">Export as excel</button> Hidden by PR -->
								</div>
								<div class="table-responsive pad-0">
									<!-- <table ng-show="transactions.length > 0" class="table table-striped table-borderless"> -->
									<table id="wallet-report" class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>Wallet Transaction ID</th>
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
											<tr ng-repeat="tx in vendorTransactions">
												<td>@{{tx.id}}</td>
												<td>@{{tx.created_at | date: 'medium'}}</td>
												<td>@{{tx.activity}}</td>
												<td>@{{tx.narration}}</td>
												<td ng-if="tx.transaction_type == 1 || tx.transaction_type == 6 || tx.transaction_type == 7">@{{tx.balance - tx.amount | currency: 'Rs. '}}</td>
												<td ng-if="tx.transaction_type == 0 || tx.transaction_type == 5">@{{ (tx.balance * 1) + (tx.amount * 1) | currency: 'Rs. ' }}</td>
												<td ng-if="tx.transaction_type == 1 || tx.transaction_type == 6 || tx.transaction_type == 7">
													<span class="label label-success">
														@{{tx.amount | currency: 'Rs. '}}
													</span>
												</td>
												<td>0</td>
												<td ng-if="tx.transaction_type == 0 || tx.transaction_type == 5">
													<span class="label label-danger">@{{tx.amount | currency: 'Rs. '}}
													</span>
												</td>
												<td>@{{tx.balance | currency: 'Rs. '}}</td>
											</tr>
										</tbody>
									</table>
									<?php Paginator::setPageName('page'); ?>
									{{ $vendorTransactions->appends(getAppendData())->links() }}
									<!-- <h5 ng-hide="transactions.length > 0">No transactions conducted yet. Click on AEPS to begin your first transaction.</h5> -->
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="panel panel-default panel-table" style="    border: 0;">
								<div class="panel-heading" style="margin-bottom: 10px;border: 1px solid #ddd;">
									<!-- <div class="tools"><span class="icon mdi mdi-more-vert"></span></div> -->
									<div class="row">
                                        <div class="col-xs-6">
                                            <span class="title">Admin Transaction</span>
									    </div>
                                        <div class="col-xs-6">
									       <button ng-click="exportFile('admin-transactions', adminTransactions)" class="btn btn-sm btn-primary pull-right" style="float: right;"><i class="fa fa-file-excel-o"></i> Export as excel</button>
                                        </div>
                                    </div>
								</div>
								<div class="table-responsive">
									<!-- <table ng-show="transactions.length > 0" class="table table-striped table-borderless"> -->
									<table class="datatable table table-striped table-bordered">
										<thead>
											<tr>
												<th>Wallet Transaction ID</th>
												<th>Transaction Date</th>
												<th>Amount</th>
												<th>Bank</th>
												<th>Branch</th>
												<th>Mode</th>
												<th>Ref No</th>
												<th>Status</th>
											</tr>
										</thead>
										<!-- <tbody class="no-border-x">
											<tr ng-repeat="tx in adminTransactions">
												<td>@{{tx.id}}</td>
												<td>@{{tx.created_at | date: 'medium' }}</td>
												<td>@{{tx.amount | currency: 'Rs. '}}</td>
												<td>@{{tx.bank}}</td>
												<td>@{{tx.branch}}</td>
												<td>@{{tx.transfer_mode}}</td>
												<td>@{{tx.reference_number}}</td>
												<td>@{{tx.status_label}}</td>
											</tr>
										</tbody> -->
									</table>
									<?php Paginator::setPageName('pag'); ?>
									<!-- {{ $adminTransactions->appends(getAppendData())->links() }} -->
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
	//var adminTransactions = {{ json_encode($adminTransactions->getItems()) }};
	var vendorTransactions = {{ json_encode($vendorTransactions->getItems()) }};        
</script>
<script>
angular.module('DIPApp')
.controller('reportsCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
	//window.s = $scope
	var modeOfTransferDict = {{ json_encode(Config::get('dictionary.MODE_OF_TRANSFER')) }}
	var walletBanksDict = {{ json_encode(Config::get('dictionary.WALLET_BANKS')) }}
	var statusDict = {0: 'Pending', 1: 'Approved', 2: 'Rejected'}
	//$scope.adminTransactions = adminTransactions.map(formatAdminTransaction)
	$scope.vendorTransactions = vendorTransactions.map(formatVendorTransaction)
	console.log(vendorTransactions)
	$scope.exportFile = exportFile
        	
	function formatAdminTransaction (tx) {
		tx.created_at = new Date(tx.created_at)
		tx.bank = walletBanksDict[tx.bank]
		tx.transfer_mode = modeOfTransferDict[tx.transfer_mode]
		tx.status_label = statusDict[tx.status]
		return tx
	}
	function formatVendorTransaction (tx) {
		tx.created_at = new Date(tx.created_at)
		return tx
	}
	function exportFile (filename, data) {
	  $http.post('/export/excel', {name: filename, rows: data.map(function (obj) {
	  	if (filename == 'vendor-transactions') {
	    	newObj = {
	    		'Wallet_Transaction_ID': obj.id,
	    		'Transaction_Date': $filter('date')(obj.created_at, 'medium'),
	    		'Activity': obj.activity,
	    		'Narration': obj.narration,
	    		'Opening_Balance': (obj.transaction_type == 1 || obj.transaction_type == 6 || obj.transaction_type == 7)? (obj.balance - obj.amount):(obj.balance + obj.amount),
	    		'Credit': (obj.transaction_type == 1 || obj.transaction_type == 6 || obj.transaction_type == 7)? (obj.amount):'0',
	    		'Debit':(obj.transaction_type == 0 || obj.transaction_type == 5)?(obj.amount):'0',
	    		'Closing_Balance':obj.balance
	    	}
	    }
	    if (filename == 'admin-transactions') {
	    	newObj = {
	    		'Wallet_Transaction_ID': obj.id,
	    		'Transaction_Date': $filter('date')(obj.created_at, 'medium'),
	    		'Amount': obj.amount,
	    		'Bank': obj.bank,
	    		'Branch': obj.branch,
	    		'Mode': obj.transfer_mode,
	    		'Ref_No': obj.reference_number,
	    		'Status': obj.status_label
	    	}
	    }
	    return newObj
	  })}).then(function (data) {
	    window.location.href = '/exports/'+data.data+'.xls'
	  }, console.log)
	}

	function fail (err) {
		
		sweetAlert('Error', 'Something went wrong', 'error');
	}
    
    /*$('#wallet-report').DataTable( {
        fixedHeader: true,
        buttons: [
            {extend: 'copy',className: 'btn-sm'},
            {extend: 'csv',title: 'ExampleFile', className: 'btn-sm'},
            {extend: 'pdf', title: 'ExampleFile', className: 'btn-sm'},
            {extend: 'print',className: 'btn-sm'}
        ],
        "data": $scope.vendorTransactions,
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
    });*/
}])
</script>
@stop