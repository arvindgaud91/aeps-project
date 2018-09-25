<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="HomeCtrl" class="head-weight">
	<div class="row">
		<div class="col-md-6 col-md-offset-3"  id="section-to-print">
			<div class="panel panel-default panel-border-color panel-border-color-primary">
				<div class="panel-heading panel-heading-divider">
					Transaction  Receipt <i style="float: right; cursor: pointer;" class="icon mdi mdi-print" onclick="window.print();"></i>
					<div class="clearfix"></div>
				</div>
				<?php
						 $domain = preg_replace('#^https?://#', '', Request::root());
						
						?>
				@if( $domain != 'aeps-rl.hamarakendra.com' || $domain != 'aeps.hamarakendra.com' || $domain != 'rb.myam-tech.com')
				<img src="/images/cinqueterre.png" height="50px;" width="50px;" style="margin-left: 20px;">
				@endif
				<img src="/images/rbl.png" align="right" height="50px;" style="margin-right:15px;">

				<div class="panel-body">
					<form style="border-radius: 0px;" class="form-horizontal group-border-dashed">
						
            @if($domain == "am-tech.digitalindiapayments.com")

						 

						
  


		<table class="table table-bordered" style="
    background: url(/images/amtech-watermak.png) no-repeat center center;">
							<thead>
								<tr>
									<th colspan="2">Customer Copy - AEPS @{{labelDict[transaction.type]}}</th>
								</tr>
							</thead>
				<tbody>
								<tr>
									<td>Date &amp; Time: </td>
									<td>@{{transaction.created_at}}</td>
								</tr>
								<tr>
									<td>Terminal ID:</td>
									<td>@{{transaction.user.vendor_details['terminal_id']}}</td>
								</tr>
								<tr>
									<td>Agent ID: </td>
									<td>@{{transaction.user.vendor_details['csr_id']}}</td>
								</tr>
								<tr>
									<td>BC Name: </td>
									<td>@{{transaction.user['name']}}</td>
								</tr>
								<tr>
									<td>mATM Req ID: </td>
									<td>@{{ transaction.user.vendor_details.device_id }}</td>
								</tr>
								<tr>
									<td>RRN: </td>
									<td>@{{transaction.rrn}}</td>
								</tr>
								<tr>
									<td>STAN: </td>
									<td>@{{transaction.stan}}</td>
								</tr>
								<tr>
									<td>Txn ID: </td>
									<td>@{{transaction.id}}</td>
								</tr>
								<tr>
									<td>UIDAI Auth Code: </td>
									<td>@{{ transaction.uidai_auth_code }}</td>
								</tr>

								<tr ng-show="transaction.result == '1'">
									<td>Txn Status: </td>

									<td ng-hide="transaction.result_message != ''">@{{statusDict[transaction.result]}} (@{{transaction.result_code || "Error"}})
									<td ng-show="transaction.result_message != ''">@{{statusDict[transaction.result]}} (@{{transaction.result_code}}) : @{{ transaction.result_message }}
									</td>
								</tr>

						        <tr ng-show="transaction.result == '0'">
									<td>Txn Status: </td>

									<td ng-hide="transaction.result_message != ''">@{{statusDict[transaction.result]}} (@{{transaction.result_code || "Error"}})
									<td ng-show="transaction.result_message != ''">@{{statusDict[transaction.result]}} (@{{transaction.result_code}}) : @{{ transaction.result_message }}
									</td>
								</tr>

								<!--<tr>
									<td>Txn Status: </td>
									<td ng-if="transaction.result_code == 00">Success
									<td ng-if="transaction.result_code != 00">Decline
									</td>
								</tr>-->
								
								<tr>
									<td>Txn Amt: </td>
									<td>@{{transaction.amount}}</td>
								</tr>
								<tr>
									<td>A/c bal: </td>
									<td>@{{transaction.balance}}</td>
								</tr>
								<tr>
								    <td>Response Code: </td>

								    <!-- <td ng-show="transaction.result_code == 'DD'">
								    	00 
								    </td>

								    <td ng-show="transaction.result_code == '91'">
								    	00 
								    </td>
								    <td ng-show="transaction.result_code == 'DS'">
								    	00 
								    </td>

								    <td ng-show="transaction.result_code == '00'">
								    	@{{transaction.result_code}}
								    </td> -->

								    <td ng-show="transaction.result =='1'">
	                             	00
	                             </td>	
	                             <td ng-show="transaction.result =='0'">
	                             	@{{ transaction.result_code }}
	                             </td>
								    
								</tr>
								    
                                      

								

								<tr>
								    <td>Response Message: </td>

								     <!-- <td ng-show="transaction.result_code == 'DD'">
								    	Success 
								    </td>

								    <td ng-show="transaction.result_code == '91'">
								    	Success 
								    </td>
								    <td ng-show="transaction.result_code == 'DS'">
								    	Success 
								    </td>

								    <td ng-show="transaction.result_code == '00'">
								    	@{{ transaction.result_message }}
								    </td> -->

                             <td ng-show="transaction.result =='1'">
                             	APPROVED
                             </td>	
                             <td ng-show="transaction.result =='0'">
                             	@{{ transaction.result_message }}
                             </td>
								    
								</tr>
							</tbody>
						</table>


 
            @else  						
							<table class="table table-bordered" style="
    background: url(/images/muthoot-watermak.png) no-repeat center center;" ng-if="transaction.user.vendor_details['parent_id'] == 1024">
							<thead>
								<tr>
									<th colspan="2">Customer Copy - AEPS @{{labelDict[transaction.type]}}</th>
								</tr>
							</thead>
					<tbody>
								<tr>
									<td>Date &amp; Time: </td>
									<td>@{{transaction.created_at}}</td>
								</tr>
								<tr>
									<td>Terminal ID:</td>
									<td>@{{transaction.user.vendor_details['terminal_id']}}</td>
								</tr>
								<tr>
									<td>Agent ID: </td>
									<td>@{{transaction.user.vendor_details['csr_id']}}</td>
								</tr>
								<tr>
									<td>BC Name: </td>
									<td>@{{transaction.user['name']}}</td>
								</tr>
								<tr>
									<td>mATM Req ID: </td>
									<td>@{{ transaction.user.vendor_details.device_id }}</td>
								</tr>
								<tr>
									<td>RRN: </td>
									<td>@{{transaction.rrn}}</td>
								</tr>
								<tr>
									<td>STAN: </td>
									<td>@{{transaction.stan}}</td>
								</tr>
								<tr>
									<td>Txn ID: </td>
									<td>@{{transaction.id}}</td>
								</tr>
								<tr>
									<td>UIDAI Auth Code: </td>
									<td>@{{ transaction.uidai_auth_code }}</td>
								</tr>

								<tr ng-show="transaction.result == '1'">
									<td>Txn Status: </td>

									<td ng-hide="transaction.result_message != ''">@{{statusDict[transaction.result]}} (@{{transaction.result_code || "Error"}})
									<td ng-show="transaction.result_message != ''">@{{statusDict[transaction.result]}} (@{{transaction.result_code}}) : @{{ transaction.result_message }}
									</td>
								</tr>

						        <tr ng-show="transaction.result == '0'">
									<td>Txn Status: </td>

									<td ng-hide="transaction.result_message != ''">@{{statusDict[transaction.result]}} (@{{transaction.result_code || "Error"}})
									<td ng-show="transaction.result_message != ''">@{{statusDict[transaction.result]}} (@{{transaction.result_code}}) : @{{ transaction.result_message }}
									</td>
								</tr>

								<!--<tr>
									<td>Txn Status: </td>
									<td ng-if="transaction.result_code == 00">Success
									<td ng-if="transaction.result_code != 00">Decline
									</td>
								</tr>-->
								
								<tr>
									<td>Txn Amt: </td>
									<td>@{{transaction.amount}}</td>
								</tr>
								<tr>
									<td>A/c bal: </td>
									<td>@{{transaction.balance}}</td>
								</tr>
								<tr>
								    <td>Response Code: </td>

								    <!-- <td ng-show="transaction.result_code == 'DD'">
								    	00 
								    </td>

								    <td ng-show="transaction.result_code == '91'">
								    	00 
								    </td>
								    <td ng-show="transaction.result_code == 'DS'">
								    	00 
								    </td>

								    <td ng-show="transaction.result_code == '00'">
								    	@{{transaction.result_code}}
								    </td> -->

								    <td ng-show="transaction.result =='1'">
	                             	00
	                             </td>	
	                             <td ng-show="transaction.result =='0'">
	                             	@{{ transaction.result_code }}
	                             </td>
								    
								</tr>
								    
                                      

								

								<tr>
								    <td>Response Message: </td>

								     <!-- <td ng-show="transaction.result_code == 'DD'">
								    	Success 
								    </td>

								    <td ng-show="transaction.result_code == '91'">
								    	Success 
								    </td>
								    <td ng-show="transaction.result_code == 'DS'">
								    	Success 
								    </td>

								    <td ng-show="transaction.result_code == '00'">
								    	@{{ transaction.result_message }}
								    </td> -->

                             <td ng-show="transaction.result =='1'">
                             	APPROVED
                             </td>	
                             <td ng-show="transaction.result =='0'">
                             	@{{ transaction.result_message }}
                             </td>
								    
								</tr>
							</tbody>
						</table>
						
						<table class="table table-bordered" ng-if="transaction.user.vendor_details['parent_id'] != 1024">
							<thead>
								<tr>
									<th colspan="2">Customer Copy - AEPS @{{labelDict[transaction.type]}}</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Date &amp; Time: </td>
									<td>@{{transaction.created_at}}</td>
								</tr>
								<tr>
									<td>Terminal ID:</td>
									<td>@{{transaction.user.vendor_details['terminal_id']}}</td>
								</tr>
								<tr>
									<td>Agent ID: </td>
									<td>@{{transaction.user.vendor_details['csr_id']}}</td>
								</tr>
								<tr>
									<td>BC Name: </td>
									<td>@{{transaction.user['name']}}</td>
								</tr>
								<tr>
									<td>mATM Req ID: </td>
									<td>@{{ transaction.user.vendor_details.device_id }}</td>
								</tr>
								<tr>
									<td>RRN: </td>
									<td>@{{transaction.rrn}}</td>
								</tr>
								<tr>
									<td>STAN: </td>
									<td>@{{transaction.stan}}</td>
								</tr>
								<tr>
									<td>Txn ID: </td>
									<td>@{{transaction.id}}</td>
								</tr>
								<tr>
									<td>UIDAI Auth Code: </td>
									<td>@{{ transaction.uidai_auth_code }}</td>
								</tr>

								<tr ng-show="transaction.result == '1'">
									<td>Txn Status: </td>

									<td ng-hide="transaction.result_message != ''">@{{statusDict[transaction.result]}} (@{{transaction.result_code || "Error"}})
									<td ng-show="transaction.result_message != ''">@{{statusDict[transaction.result]}} (@{{transaction.result_code}}) : @{{ transaction.result_message }}
									</td>
								</tr>

						        <tr ng-show="transaction.result == '0'">
									<td>Txn Status: </td>

									<td ng-hide="transaction.result_message != ''">@{{statusDict[transaction.result]}} (@{{transaction.result_code || "Error"}})
									<td ng-show="transaction.result_message != ''">@{{statusDict[transaction.result]}} (@{{transaction.result_code}}) : @{{ transaction.result_message }}
									</td>
								</tr>

								<!--<tr>
									<td>Txn Status: </td>
									<td ng-if="transaction.result_code == 00">Success
									<td ng-if="transaction.result_code != 00">Decline
									</td>
								</tr>-->
								
								<tr>
									<td>Txn Amt: </td>
									<td>@{{transaction.amount}}</td>
								</tr>
								<tr>
									<td>A/c bal: </td>
									<td>@{{transaction.balance}}</td>
								</tr>
								<tr>
								    <td>Response Code: </td>

								    <!-- <td ng-show="transaction.result_code == 'DD'">
								    	00 
								    </td>

								    <td ng-show="transaction.result_code == '91'">
								    	00 
								    </td>
								    <td ng-show="transaction.result_code == 'DS'">
								    	00 
								    </td>

								    <td ng-show="transaction.result_code == '00'">
								    	@{{transaction.result_code}}
								    </td> -->

								    <td ng-show="transaction.result =='1'">
	                             	00
	                             </td>	
	                             <td ng-show="transaction.result =='0'">
	                             	@{{ transaction.result_code }}
	                             </td>
								    
								</tr>
								    
                                      

								

								<tr>
								    <td>Response Message: </td>

								     <!-- <td ng-show="transaction.result_code == 'DD'">
								    	Success 
								    </td>

								    <td ng-show="transaction.result_code == '91'">
								    	Success 
								    </td>
								    <td ng-show="transaction.result_code == 'DS'">
								    	Success 
								    </td>

								    <td ng-show="transaction.result_code == '00'">
								    	@{{ transaction.result_message }}
								    </td> -->

                             <td ng-show="transaction.result =='1'">
                             	APPROVED
                             </td>	
                             <td ng-show="transaction.result =='0'">
                             	@{{ transaction.result_message }}
                             </td>
								    
								</tr>
							</tbody>
						</table>
						
						<p><b><i>Note: Pls do not pay any charges/fee for this txn.</i></b></p>
						@endif
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@section('javascript')
<script>
angular.module('DIPApp')
	.controller('HomeCtrl', ['$scope', '$http', function ($scope, $http) {
		//window.s = $scope
		$scope.transaction = {{ $transaction }};
		$scope.labelDict = {
		         '0': 'Balance Enquiry',
		         '1':  'Deposit',
		         '2':'Withdraw'
      		}

      		$scope.statusDict={
      			'0':'Failed',
      			'1':'Success'
      		}

		//console.log($scope.transaction);
		function fail (err) {
		//console.log(err)
		sweetAlert('Error', 'Something went wrong', 'error')
	}
}])
</script>
@stop
