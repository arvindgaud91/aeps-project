<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<?php $user_id = Auth::user()->id;?>
<div ng-controller="reportsCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">BC Agents Reports<span class="panel-subtitle">Snapshot</span></div>
				<br>
				<div class="panel-body">
					<!-- <div class="row">
						<div class="col-md-12">
							<button ng-click="exportFile('aeps-bcAgents', bcAgents)" class="btn btn-primary" style="float: right;">Export as excel</button>
						</div>
					</div> -->
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default panel-table">
								<div class="panel-body table-responsive">
									<table class="table table-striped table-borderless" ng-show="bcAgents.length>0">
										<thead>
											<tr>
												<th>IMEI No.</th>
												<th>BC Agent Name</th>
												<th>Middle Name</th>
												<th>Last Name</th>
												<th>Gender</th>
												<th>Name of the Establishment</th>
												<th>Education</th>
												<th style="width:200px;">Outlet Address</th>
												<th>Area</th>
												<th>City</th>
												<th>District</th>
												<th>State</th>
												<th>Pin Code</th>
												<th>Mobile No</th>
												<th>Resedetial Address</th>
												<th>Resedetial Area</th>
												<th>Resedetial City</th>
												<th>Resedetial Pin Code</th>
												<th>Telephone NO</th>
												<th>Alternate NO</th>
												<th>Email ID</th>
												<th>Date of Birth</th>
												<th>Description of Services </th>
												<th>Pancard</th>
												<th>Oprating Hours</th>
												<th>Weekly-Off</th>
												<th>Bank Name</th>
												<th>Account Type</th>
												<th>Account Number</th>
												<th>IFSC Code</th>
												<th>Address Proof</th>
												<th>ID Proof</th>
												<th>Status</th>
												<th>Registration Form</th>
												<th>Update Registration Form</th>
											</tr>
										</thead>
										<tbody class="no-border-x">
											<tr ng-repeat="bcAgent in bcAgents">
												<td>@{{bcAgent.imei_no}}</td>
												<td>@{{bcAgent.bc_agent_name}}</td>
												<td>@{{bcAgent.middle_name}}</td>
												<td>@{{bcAgent.last_name}}</td>
												<td ng-if="bcAgent.gender == 0">Male</td>
												<td ng-if="bcAgent.gender == 1">Female</td>
												<td ng-if="bcAgent.gender == 2">Transgender</td>
												<td>@{{bcAgent.establishment}}</td>
												<td>@{{bcAgent.education}}</td>
												<td style="width:200px;">@{{bcAgent.address}}</td>
												<td>@{{bcAgent.area}}</td>
												<td>@{{bcAgent.city}}</td>
												<td>@{{bcAgent.district}}</td>
												<td>@{{bcAgent.state}}</td>
												<td>@{{bcAgent.pincode}}</td>
												<td>@{{bcAgent.mobile_number}}</td>
												<td>@{{bcAgent.laddress}}</td>
												<td>@{{bcAgent.larea}}</td>
												<td>@{{bcAgent.lcity}}</td>
												<td>@{{bcAgent.lpincode}}</td>
												<td>@{{bcAgent.telephone}}</td>
												<td>@{{bcAgent.alternate_number}}</td>
												<td>@{{bcAgent.email_address}}</td>
												<td>@{{bcAgent.date_of_birth}}</td>
												<td>@{{bcAgent.description_of_outlet}}</td>
												<td>@{{bcAgent.pancard}}</td>
												<td ng-if="bcAgent.operating_hours == 0">8:00 AM to 4:00 PM</td>
												<td ng-if="bcAgent.operating_hours == 1">9:00 AM to 5:00 PM</td>
												<td ng-if="bcAgent.operating_hours == 2">10:00 AM to 6:00 PM</td>
												<td ng-if="bcAgent.operating_hours == 3">11:00 AM to 7:00 PM</td>
												<td ng-if="bcAgent.weekly_off == 0">SUNDAY</td>
												<td ng-if="bcAgent.weekly_off == 1">MONDAY</td>
												<td ng-if="bcAgent.weekly_off == 2">TUESDAY</td>
												<td ng-if="bcAgent.weekly_off == 3">WEDNESDAY</td>
												<td ng-if="bcAgent.weekly_off == 4">THURSDAY</td>
												<td ng-if="bcAgent.weekly_off == 5">FRIDAY</td>
												<td ng-if="bcAgent.weekly_off == 6">SATURDAY</td>
												<td>@{{bcAgent.bank_name}}</td>
												<td ng-if="bcAgent.account_type == 0">Current</td>
												<td ng-if="bcAgent.account_type == 1">Saving</td>
												<td>@{{bcAgent.account_number}}</td>
												<td>@{{bcAgent.ifsc}}</td>
												<td><a target="_blank" class="btn-success btn btn-xs" href="/upload/kyc/@{{bcAgent.addressproofurl}}">View</a></td>
												<td><a target="_blank" class="btn-success btn btn-xs" href="/upload/kyc/@{{bcAgent.idproofurl}}">View</a></td>
												<td ng-if="bcAgent.status == 0">Pending</td>
												<td ng-if="bcAgent.status == 1">Request Accepted</td>
												<td ng-if="bcAgent.status == 2">Approved</td>
												<td ng-if="bcAgent.status == 3">Rejected</td>
												<td><a target="_blank" href="registration-form/@{{bcAgent.id}}">Form</a></td>
												<td><a class="btn-warning btn btn-xs"  href="registration-update-pdf-form/@{{bcAgent.id}}">Update Form</a></td>
											</tr>
										</tbody>
									</table>
									{{ $bcAgentObj->appends(getAppendData())->links() }}
									<?php
					                    function getAppendData ()
					                    {
					                      return [];
					                    }
					                  ?>
									<h5 ng-hide="bcAgents.length > 0">No BC Agents.</h5>
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
	var bcAgents = {{json_encode($bcAgents) }};

</script>
<script>
angular.module('DIPApp')
.controller('reportsCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {

	$scope.bcAgents = bcAgents

	console.log($scope.bcAgents)

	$scope.exportFile = exportFile
	

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
}])
</script>
@stop