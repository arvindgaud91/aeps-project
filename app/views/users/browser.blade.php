<?php use Acme\Auth\Auth;?>
@extends('layouts.master')
@section('content')
  <div ng-controller="ResetSessionCtrl" class="head-weight">
    <div class="row">
        <div class="col-lg-12">
      <h3>Mozilla Browser</h3>
      <div class="panel panel-default">
        <div class="panel-body">
            
        LINK :- <a href="https://127.0.0.1:8005/rd/capture" target="_blank">https://127.0.0.1:8005/rd/capture</a>


            
        
        </div>
      </div>
  </div>
  <div class="col-lg-12">
      <h3>Chrome Browser</h3>
      <div class="panel panel-default">
        <div class="panel-body">
            
       <p> Simply Copy below URL and paste it into new chrome tab:</p></br>
       <p>

 LINK :- <b><p>chrome://flags/#allow-insecure-localhost</p></b></br>
 
 <p> In a new tab below screen appears, then click on enable button And relaunch browser. <h3 style="
    color: red;">
</br>


   <img src="http://aeps.digitalindiapayments.com/images/chrome_enabled.png">         
        
        </div>
      </div>
  </div>
    </div>
  </div>
@stop

@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('ResetSessionCtrl', ['$scope', '$http', function ($scope, $http) {
      $scope.submit = submit
    

      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
