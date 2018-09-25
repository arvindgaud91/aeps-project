<?php

use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="DeviceDetails" class="head-weight">
    <div class="row">
  <div class="col-md-12">
    <div class="panel panel-default panel-border-color panel-border-color-primary">
      
      <div class="panel-body">
        <div class="col-md-5">
          </div>
          @if($data=='')
          <button class="btn btn-success" type="button" ng-click="getdevice()">getDeviceDetails</button>
          @endif
     

      </div>
    </div>
  </div>
 
</div>

    @stop

    @section('javascript')
    <script>
        
        angular.module('DIPApp')
                .controller('DeviceDetails', ['$scope', '$http', function ($scope, $http) {

                window.s = $scope 

               $scope.submit = submit
                $scope.getdevice = getdevice
                function getdevice()
              {
             console.log({{$user = Auth::user()->id
}})
                  $http.get('https://localhost:15005/getDeviceDetails')
                  .then(function (data) {
                   console.log(data.data.DeviceSerial);
                   $scope.DeviceMake=data.data.DeviceMake
                   $scope.DeviceModel=data.data.DeviceModel
                   $scope.DeviceSerial=data.data.DeviceSerial
                   
                   if($scope.DeviceSerial !=null & $scope.DeviceModel !=null & $scope.DeviceMake !=null){
                     submit($scope.DeviceMake,$scope.DeviceModel, $scope.DeviceSerial)
                   }
                 
                  })
                
                 //submit(3,4, 5)
              }
              function submit (deviceMake,deviceModel,deviceSerial) 
              {
               
                 
                 var transaction = Object.assign({'deviceMake': deviceMake, 'deviceModel': deviceModel,'deviceSerial':deviceSerial});
                
                 $http.post('/store-device-details/'+{{$user = Auth::user()->id
}}, transaction)
                  .then(function (data) {
                   
                    oldToastr.success('Thanks for feedback')
                    setTimeout(function () {
                     location.reload();
                   }, 1500)
                  }, fail)
                }
 

                function fail (err) {
               
                        sweetAlert('Error', 'Something went wrong', 'error')
                }
                }])
    </script>
    @stop
