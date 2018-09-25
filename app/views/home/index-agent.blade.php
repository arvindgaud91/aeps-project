<?php 
use Acme\Auth\Auth; 
$user = Auth::user();
?>
@extends('layouts.master')
@section('content')

<div ng-controller="HomeCtrl" class="head-weight">
    <div class="row">
            <div class="col-lg-12 text-center welcome-message">

         <?php $domain_data = preg_replace('#^https?://#', '', Request::root()); ?>
        @if($domain_data == 'am-tech.digitalindiapayments.com')
            <h2 class="col-md-12 text-center">
                Welcome to  Am-Tech
            </h2>
        @elseif($domain_data == 'service.aepsmoney.com')
            <h2 class="col-md-12 text-center">
                 Welcome to AMIABLE COMTRADE AND ONLINE SHOP PVT.LTD
            </h2>
        @elseif($domain_data == 'aeps.unnayon.in')
            <h2>
                Welcome to UNNAYON CONSULTANCY SERVICES PVT LTD
            </h2>
        @elseif($domain_data == 'aeps.hamarakendra.com')
            <h2>
                 Welcome to IPS e Services Pvt. Ltd.
            </h2>
        @elseif($domain_data == 'aeps-rl.hamarakendra.com')
            <h2>
                 Welcome to IPS e Services Pvt. Ltd.
            </h2>
        @elseif($domain_data == 'payments.digitalworldpaymentshub.com')
            <h2>
                Welcome to DIGITAL WORLD PAYMENTS HUB
            </h2>
        @elseif($domain_data == 'aeps.himveda.co.in')
            <h2>
                Himveda E-Solution Pvt.Ltd.
            </h2>
         @elseif($domain_data == 'wallet.reijiro.co.in')
            <h2>
                Reijiro
            </h2>
        @elseif($domain_data == 'aeps.acospay.com')
            <h2>
                Welcome to AMIABLE COMTRADE AND ONLINE SHOP PVT.LTD
            </h2>
        @elseif($domain_data == 'rb.myam-tech.com')
            <h2></h2>
        @else
            <h2>
                Welcome to Digital India Payments Ltd
            </h2>
        @endif
                

                <!-- <p>
                    Special <strong>Admin Theme</strong> for medium and large web applications with very clean and
                    aesthetic style and feel.
                </p> -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel animate-panel">
                    <div class="panel-heading">
                        <!-- <div class="panel-tools">
                            <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                            <a class="closebox"><i class="fa fa-times"></i></a>
                        </div> -->
                        Dashboard information and statistics
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="small">
                                    <i class="fa fa-bolt"></i> Today's
                                </div>
                                <div>
                                    <h2 class="font-extra-bold m-t-xl m-b-xs">
                                        <?php echo date("dS M Y");?>
                                    </h2>
                                    <!-- <small>Company Agent Views</small>-->
                                </div>
                                <div class="small m-t-xl">
                                    <i class="fa fa-clock-o"></i> Data from {{ @date('Y') }}
                                    <i class="fa fa-bolt"></i> Today
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center small">
                                    <i class="fa fa-laptop"></i> Active users in current month ({{ @date('Y') }})
                                </div>
                                <div class="flot-chart" style="height: 160px">
                                    <div class="flot-chart-content" id="flot-line-chart"></div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="small">
                                    <i class="fa fa-clock-o"></i> Active duration
                                </div>
                                <?php
                                $then = $user->created_at;
                                $then = new DateTime($then);
                                 
                                $now = new DateTime();
                                 
                                $sinceThen = $then->diff($now);
                                 
                                ?>
                                <div>
                                    <h1 class="font-extra-bold m-t-xl m-b-xs">
                                        <?php echo $sinceThen->m; ?>Months
                                    </h1>
                                    <small>And <?php echo $sinceThen->d; ?> days</small>
                                </div>
                                <!-- <div class="small m-t-xl">
                                    <i class="fa fa-clock-o"></i> Last active in 12.10.2015
                                </div> -->
                                
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                    <span class="pull-right">
                          You have two new messages from <a href="">Monica Bolt</a>
                    </span>
                        Last update: 21.05.2015
                    </div>-->
                </div>
            </div>
        </div>
    <div class="animate-panel">
        <div class="row">
            <div class="panel-heading">
                <strong>Today's Overview</strong>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Deposit</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-download fa-4x c-red"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-5">
                                    <small class="stat-label">Count</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ deposit_count_today}}</span></h4> -->
                                    <h4><span data-toggle="counter" data-end="113">@{{ deposit_count_today}}</span></h4>
                                </div>
                                <div class="col-xs-7">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ deposit_amount_today || 0 }}</span> <i class="fa fa-level-up text-success"></i></h4> -->
                                    <h4><i class="fa fa-rupee"></i> <span data-toggle="counter" data-end="113">@{{ deposit_amount_today || 0 }}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Today's Deposit Overview
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Withdraw</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-upload fa-4x c-orange"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-5">
                                    <small class="stat-label">Count</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ withdraw_count_today }}</span></h4> -->
                                    <h4>
                                        <span data-toggle="counter" data-end="113">@{{ withdraw_count_today }}</span>
                                    </h4>
                                    
                                </div>
                                <div class="col-xs-7">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ withdraw_amount_today || 0 }}</span> <i class="fa fa-level-up text-success"></i></h4> -->
                                    <h4>
                                        <i class="fa fa-rupee"></i> 
                                        <span data-toggle="counter" data-end="113" class="number value">@{{ withdraw_amount_today || 0 }}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Today's Withdraw Overview
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Balance Enquiry</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-check fa-4x c-purple"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-5">
                                    <small class="stat-label">Count</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ balance_enquiry_count_today }}</span></h4> -->
                                    <h4>
                                        <span data-toggle="counter value" data-end="113">@{{ balance_enquiry_count_today }}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Today's Balance Enquiry Overview
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Commission</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-cash fa-4x c-green"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-7 col-xs-offset-5">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ withdraw_amount_today || 0 }}</span> <i class="fa fa-level-up text-success"></i></h4> -->
                                    <h4>
                                        <i class="fa fa-rupee"></i> 
                                        <span data-toggle="counter" data-end="113">@{{commission_today || 0}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Today's Commission Overview
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel-heading">
                <strong>Weekly Overview</strong>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Deposit</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-download fa-4x c-red"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-5">
                                    <small class="stat-label">Count</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ deposit_count_weekly}}</span></h4> -->
                                    <h4>
                                        <span data-toggle="counter" data-end="113">@{{ deposit_count_weekly}}</span>
                                    </h4>
                                    
                                </div>
                                <div class="col-xs-7">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ deposit_amount_weekly || 0}}</span> <i class="fa fa-level-up text-success"></i></h4> -->
                                    <h4>
                                        <i class="fa fa-rupee"></i> 
                                        <span data-toggle="counter" data-end="113">@{{ deposit_amount_weekly || 0}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Weekly Deposit Overview
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Withdraw</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-upload fa-4x c-orange"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-5">
                                    <small class="stat-label">Count</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ withdraw_count_weekly}}</span></h4> -->
                                    <h4>
                                        <span data-toggle="counter" data-end="113">@{{ withdraw_count_weekly}}</span>
                                    </h4>
                                </div>
                                <div class="col-xs-7">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ withdraw_amount_weekly || 0 }}</span> <i class="fa fa-level-up text-success"></i></h4> -->
                                    <h4>
                                        <i class="fa fa-rupee"></i> 
                                        <span data-toggle="counter" data-end="113">@{{ withdraw_amount_weekly || 0 }}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Weekly Withdraw Overview
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Balance Enquiry</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-check fa-4x c-purple"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-5">
                                    <small class="stat-label">Count</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ balance_enquiry_count_weekly}}</span></h4> -->
                                    <h4>
                                        <span data-toggle="counter" data-end="113">@{{ balance_enquiry_count_weekly}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Weekly Balance Enquiry Overview
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Commission</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-cash fa-4x c-green"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-7 col-xs-offset-5">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ withdraw_amount_today || 0 }}</span> <i class="fa fa-level-up text-success"></i></h4> -->
                                    <h4>
                                        <i class="fa fa-rupee"></i> 
                                        <span data-toggle="counter" data-end="113">@{{ commission_weekly || 0 }}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Weekly Commission Overview
                    </div>
                </div>
            </div>
        </div>

        <!-- <form method="post" action="/getauth" name="f1">
<input type="hidden" name="auth" value=" ">
        </form> -->
        <?php /**
        <div class="row">
            <div class="panel-heading">
                <strong>Monthly Overview</strong>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Deposit</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-download fa-4x c-red"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-5">
                                    <small class="stat-label">Count</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ deposit_count_monthly}}</span></h4> --><!--
                                    <h4>
                                        <span data-toggle="counter" data-end="113">@{{ deposit_count_monthly}}</span>
                                    </h4>
                                </div>
                                <div class="col-xs-7">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ deposit_amount_monthly || 0}}</span> <i class="fa fa-level-up text-success"></i></h4> --><!--
                                    <h4><i class="fa fa-rupee"></i> 
                                        <span data-toggle="counter" data-end="113">@{{ deposit_amount_monthly || 0}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Monthly Deposit Overview
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Withdraw</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-upload fa-4x c-orange"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-5">
                                    <small class="stat-label">Count</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ withdraw_count_monthly}}</span></h4> --><!--
                                    <h4>
                                        <span data-toggle="counter" data-end="113">@{{ withdraw_count_monthly}}</span>
                                    </h4>
                                </div>
                                <div class="col-xs-7">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ withdraw_amount_monthly || 0}}</span> <i class="fa fa-level-up text-success"></i></h4> --><!--
                                    <h4><i class="fa fa-rupee"></i> 
                                        <span data-toggle="counter" data-end="113">@{{ withdraw_amount_monthly || 0}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Monthly Withdraw Overview
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Balance Enquiry</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-check fa-4x c-purple"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-5">
                                    <small class="stat-label">Count</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ balance_enquiry_count_monthly}}</span> </h4> --><!--
                                    <h4>
                                        <span data-toggle="counter" data-end="113">@{{ balance_enquiry_count_monthly}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Monthly Balance Enquiry Overview
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Commission</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="pe-7s-cash fa-4x c-green"></i>
                        </div>
                        <div class="clearfix"></div>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-income-chart"></div>
                        </div>
                        <div class="m-t-xs">

                            <div class="row">
                                <div class="col-xs-7 col-xs-offset-5">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ withdraw_amount_today || 0 }}</span> <i class="fa fa-level-up text-success"></i></h4> --><!--
                                    <h4>
                                        <i class="fa fa-rupee"></i> 
                                        <span data-toggle="counter" data-end="113">@{{ commission_monthly || 0 }}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        Monthly Commission Overview
                    </div>
                </div>
            </div>
        </div>
        **/ ?>
    </div>

</div>
@stop
@section('javascript')
<script>
angular.module('DIPApp')
.controller('HomeCtrl', ['$scope', '$http', function ($scope, $http) {
//window.s = $scope;
$scope.deposit_amount_today = {{ $deposit_amount_today }}
$scope.deposit_count_today = {{ $deposit_count_today }}
$scope.withdraw_amount_today = {{ $withdraw_amount_today }}
$scope.withdraw_count_today = {{ $withdraw_count_today }}
$scope.balance_enquiry_count_today = {{ $balance_enquiry_count_today }}
$scope.commission_today = {{ $commission_today }}
$scope.deposit_amount_weekly = {{ $deposit_amount_weekly }}
$scope.deposit_count_weekly = {{ $deposit_count_weekly }}
$scope.withdraw_amount_weekly = {{ $withdraw_amount_weekly }}
$scope.withdraw_count_weekly = {{ $withdraw_count_weekly }}
$scope.balance_enquiry_count_weekly = {{ $balance_enquiry_count_weekly }}
$scope.commission_weekly = {{ $commission_weekly }}


}])
</script>
<script type="text/javascript">
            document.f1.submit();
        </script>
@stop
