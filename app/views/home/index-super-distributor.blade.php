<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="HomeCtrl" class="head-weight animate-panel">
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
        @elseif($domain_data == 'aeps.acospay.com')
            <h2>
                Welcome to AMIABLE COMTRADE AND ONLINE SHOP PVT.LTD
            </h2>
        @elseif($domain_data == 'aeps.himveda.co.in')
            <h2>
                 Welcome to Himveda E-Solution Pvt.Ltd.
            </h2>
        @elseif($domain_data == 'wallet.reijiro.co.in')
            <h2>
                 Welcome to Reijiro.
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
        <div class="panel-heading">Super Distributor Dashboard</div>
            
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Total Agents</h4>
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
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">@{{ childCount}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                        This is standard panel footer
                    </div>-->
                </div>
            </div>
        <div class="col-lg-4">
                    <div class="hpanel stats">
                        <div class="panel-body h-200">
                            <div class="stats-title pull-left">
                                <h4>Total Distributors</h4>
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
                                    <div class="col-xs-12">
                                        <h4>
                                            <span data-toggle="counter" class="number">@{{ distributor_count}}</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="panel-footer">
                            This is standard panel footer
                        </div>-->
                    </div>
                </div>
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Total Balance</h4>
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
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">@{{ childBalance | currency: 'Rs. '}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                        This is standard panel footer
                    </div>-->
                </div>
            </div>
    </div>
    <div class="row">
        <div class="panel-heading">Today's Overview</div>
            
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Total Number Of Transactions</h4>
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
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">@{{ transactions_today}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                        This is standard panel footer
                    </div>-->
                </div>
            </div>
        <div class="col-lg-4">
                    <div class="hpanel stats">
                        <div class="panel-body h-200">
                            <div class="stats-title pull-left">
                                <h4>Total Amount</h4>
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
                                    <div class="col-xs-12">
                                        <h4>
                                            <span data-toggle="counter" class="number">@{{ amount_today
                        | currency: 'Rs. '}}</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="panel-footer">
                            This is standard panel footer
                        </div>-->
                    </div>
                </div>
        <?php
            $domain_data = preg_replace('#^https?://#', '', Request::root());
        ?>
        @if(!($domain_data == 'am-tech.digitalindiapayments.com'))
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Commission Earned</h4>
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
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">@{{ distributor_commission_today | currency: 'Rs. '}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                        This is standard panel footer
                    </div>-->
                </div>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="panel-heading">Weekly Overview</div>
            
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Total Number Of Transactions</h4>
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
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">@{{ transactions_weekly}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                        This is standard panel footer
                    </div>-->
                </div>
            </div>
        <div class="col-lg-4">
                    <div class="hpanel stats">
                        <div class="panel-body h-200">
                            <div class="stats-title pull-left">
                                <h4>Total Amount</h4>
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
                                    <div class="col-xs-12">
                                        <h4>
                                            <span data-toggle="counter" class="number">@{{ amount_weekly | currency: 'Rs. '}}</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="panel-footer">
                            This is standard panel footer
                        </div>-->
                    </div>
                </div>
        <?php
            $domain_data = preg_replace('#^https?://#', '', Request::root());
        ?>
        @if(!($domain_data == 'am-tech.digitalindiapayments.com'))
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Commission Earned</h4>
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
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">@{{ distributor_commission_weekly | currency: 'Rs. '}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                        This is standard panel footer
                    </div>-->
                </div>
            </div>
        @endif
    </div>
    <?php /**
    <div class="row">
        <div class="panel-heading">Monthly Overview</div>
            
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Total Number Of Transactions</h4>
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
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">@{{ transactions_monthly}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        This is standard panel footer
                    </div>
                </div>
            </div>
                <div class="col-lg-4">
                    <div class="hpanel stats">
                        <div class="panel-body h-200">
                            <div class="stats-title pull-left">
                                <h4>Total Amount</h4>
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
                                    <div class="col-xs-12">
                                        <h4>
                                            <span data-toggle="counter" class="number">@{{ amount_monthly | currency: 'Rs. '}}</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="panel-footer">
                            This is standard panel footer
                        </div>-->
                    </div>
                </div>
        <?php
            $domain_data = preg_replace('#^https?://#', '', Request::root());
        ?>
        @if(!($domain_data == 'am-tech.digitalindiapayments.com'))
        <div class="col-lg-4">
                <div class="hpanel stats">
                    <div class="panel-body h-200">
                        <div class="stats-title pull-left">
                            <h4>Commission Earned</h4>
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
                                <div class="col-xs-12">
                                    <h4>
                                        <span data-toggle="counter" class="number">@{{ distributor_commission_monthly | currency: 'Rs. '}}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="panel-footer">
                        This is standard panel footer
                    </div>-->
                </div>
            </div>
        @endif
    </div>
    **/ ?>
</div>
@stop
@section('javascript')
<script>
angular.module('DIPApp')
.controller('HomeCtrl', ['$scope', '$http', function ($scope, $http) {

window.s = $scope;
$scope.distributor_count={{$distributor_count}}
$scope.childCount = {{ $childCount }}
$scope.childBalance = {{ $childBalance }}


$scope.transactions_today= {{ $transactions_today }}
$scope.amount_today= {{ $amount_today }}
$scope.distributor_commission_today= {{ $distributor_commission_today }}

$scope.transactions_weekly= {{ $transactions_weekly }}
$scope.amount_weekly= {{ $amount_weekly }}
$scope.distributor_commission_weekly= {{ $distributor_commission_weekly }}

$scope.transactions_monthly= {{ $transactions_monthly }}
$scope.amount_monthly= {{ $amount_monthly }}
$scope.distributor_commission_monthly= {{ $distributor_commission_monthly }}
}])
</script>

@stop