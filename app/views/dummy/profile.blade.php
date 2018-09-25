<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="ProfileCtrl" class="head-weight">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">User profile</div>
                <div class="tab-container ">
                    <!--  -->
                    <div class="tab-content ">
                        <div id="profile">
                            <div class="panel panel-default panel-default panel-border-color panel-border-color-primary">
                                <div class="panel-body">
                                     <div class="panel-heading text-center">Basic Details</div>
                                    <form action="" method="post" style="border-radius: 0px;" class="form-horizontal group-border-dashed">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" >USER ID</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="userid" value="@{{activeUserProfile['id']}}" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group"  ng-hide="profileType=='superdistributor'">
                                            <label class="col-sm-3 control-label">@{{typeDic[profileType]}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="distributor" value="" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">NAME</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="name" value="@{{activeUserProfile['name']}}" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">EMAIL</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="email" value="@{{activeUserProfile['email']}}" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">MOBILE NO</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="mobile_no" value="@{{activeUserProfile['phone_no']}}" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">PAN NO</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="mobile_no" value="" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">CITY</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="city" value="" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">STATE</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="state" value="" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">ZONE</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="zone" value="" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                        <div id="advance_details" ng-hide="profileType=='distributor' || profileType=='superdistributor'">
                                            <div class="panel-heading text-center">Advance Details</div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" >CSR ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="bank_id" value="" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">DEVICE ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="device_id" value="" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">DEVICE SERIAL NO.</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="device_serial_no" value="" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">STATUS</label>
                                            <div class="col-sm-9">
                                                <div role="alert" class="alert alert-success alert-icon alert-icon-border alert-dismissible">
                                                    <div class="icon"><span class="mdi mdi-check"></span></div>
                                                    <div class="message">
                                                        <strong>Active</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('javascript')
<script>
angular.module('DIPApp')
.controller('ProfileCtrl', ['$scope', '$http', function ($scope, $http) {
    //window.s = $scope;
      $scope.profileType = {{ json_encode($profileType) }};
      $scope.typeDic = {
         'agent': 'DISTRIBUTOR',
          'distributor': 'SUPER DISTRIBUTOR',
      }
    function fail (err) {
        //console.log(err)
        sweetAlert('Error', 'Something went wrong', 'error')
    }
}])
</script>
@stop