<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="HomeCtrl" class="head-weight animate-panel">
    <div class="row">
        <div class="panel-heading">Today's Overview</div>
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
                                    <h4><span data-toggle="counter" data-end="113" class="number">@{{ deposit_count_today}}</span></h4>
                                </div>
                                <div class="col-xs-7">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ deposit_amount_today || 0 }}</span> <i class="fa fa-level-up text-success"></i></h4> -->
                                    <h4><i class="fa fa-rupee"></i><span data-toggle="counter" data-end="113" class="number"> @{{ deposit_amount_today || 0 }}</span></h4>
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
                                        <span data-toggle="counter" data-end="113" class="number">@{{ withdraw_count_today }}</span>
                                    </h4>
                                    
                                </div>
                                <div class="col-xs-7">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ withdraw_amount_today || 0 }}</span> <i class="fa fa-level-up text-success"></i></h4> -->
                                    <h4>
                                        <i class="fa fa-rupee"></i> 
                                        <span data-toggle="counter" data-end="113" class="number"> @{{ withdraw_amount_today || 0 }}</span>
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
                                        <span data-toggle="counter" data-end="113" class="number">@{{ balance_enquiry_count_today }}</span>
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
                                        <span data-toggle="counter" data-end="113" class="number"> @{{commission_today || 0}}</span>
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
        <div class="panel-heading">Weekly Overview</div>
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
                                    <h4><span data-toggle="counter" data-end="113" class="number">@{{ deposit_count_weekly}}</span></h4>
                                </div>
                                <div class="col-xs-7">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ deposit_amount_today || 0 }}</span> <i class="fa fa-level-up text-success"></i></h4> -->
                                    <h4><i class="fa fa-rupee"></i><span data-toggle="counter" data-end="113" class="number"> @{{ deposit_amount_weekly || 0}}</span></h4>
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
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ withdraw_count_today }}</span></h4> -->
                                    <h4>
                                        <span data-toggle="counter" data-end="113" class="number">@{{ withdraw_count_weekly}}</span>
                                    </h4>
                                    
                                </div>
                                <div class="col-xs-7">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ withdraw_amount_today || 0 }}</span> <i class="fa fa-level-up text-success"></i></h4> -->
                                    <h4>
                                        <i class="fa fa-rupee"></i> 
                                        <span data-toggle="counter" data-end="113" class="number"> @{{ withdraw_amount_weekly || 0 }}</span>
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
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ balance_enquiry_count_today }}</span></h4> -->
                                    <h4>
                                        <span data-toggle="counter" data-end="113" class="number">@{{ balance_enquiry_count_weekly}}</span>
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
                                        <span data-toggle="counter" data-end="113" class="number"> @{{ commission_weekly || 0 }}</span>
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
    <!--
    <div class="row">
        <div class="panel-heading">Monthly Overview</div>
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
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ deposit_count_today}}</span></h4> --><!--
                                    <h4><span data-toggle="counter" data-end="113" class="number">@{{ deposit_count_monthly}}</span></h4>
                                </div>
                                <div class="col-xs-7">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ deposit_amount_today || 0 }}</span> <i class="fa fa-level-up text-success"></i></h4> --><!--
                                    <h4><i class="fa fa-rupee"></i><span data-toggle="counter" data-end="113" class="number"> @{{ deposit_amount_monthly || 0}}</span></h4>
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
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ withdraw_count_today }}</span></h4> --><!--
                                    <h4>
                                        <span data-toggle="counter" data-end="113" class="number">@{{ withdraw_count_monthly}}</span>
                                    </h4>
                                    
                                </div>
                                <div class="col-xs-7">
                                    <small class="stat-label">Amount</small>
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number"><i class="fa fa-rupee"></i> @{{ withdraw_amount_today || 0 }}</span> <i class="fa fa-level-up text-success"></i></h4> --><!--
                                    <h4>
                                        <i class="fa fa-rupee"></i> 
                                        <span data-toggle="counter" data-end="113" class="number"> @{{ withdraw_amount_monthly || 0}}</span>
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
                                    <!-- <h4 class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span data-toggle="counter" data-end="113" class="number">@{{ balance_enquiry_count_today }}</span></h4> --><!--
                                    <h4>
                                        <span data-toggle="counter" data-end="113" class="number">@{{ balance_enquiry_count_monthly}}</span>
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
                                        <span data-toggle="counter" data-end="113" class="number"> @{{ commission_monthly || 0 }}</span>
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
    -->
</div>
@stop
@section('javascript')
<script>
angular.module('DIPApp')
.controller('HomeCtrl', ['$scope', '$http', function ($scope, $http) {
window.s = $scope;




}])
</script>
@stop
