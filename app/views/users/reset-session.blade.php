<?php use Acme\Auth\Auth;?>
@extends('layouts.master')
@section('content')
  <div ng-controller="ResetSessionCtrl" class="head-weight">
    <div class="row">
        <div class="col-lg-6">
      <h3>Reset Session</h3>
      <div class="panel panel-default">
        <div class="panel-body">
            
        <form class="form-inline" name="ResetSessionFrm" ng-submit="submit(resetSession)" novalidate>
            <div class="form-group pad-r-25">
                <label class="form-check-label">
                      <input class="form-control form-check-input" type="radio" name="radio" ng-model="enabled" value='enabled' ng-checked="<?php echo (Auth::user()->reset_session=='1'?'true':'false'); ?>">
                       Enabled
                </label>
            </div>
            <div class="form-group pad-r-25">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="radio" ng-model="disabled" value='disabled' ng-checked="<?php echo (Auth::user()->reset_session=='0'?'true':'false'); ?>">
                    Disabled
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
            
          <!--<form name="ResetSessionFrm" ng-submit="submit(resetSession)" novalidate>
            <div class="row" style="margin-top:20px">
                <div class="col-lg-6">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input" type="radio" name="radio" ng-model="enabled" value='enabled' ng-checked="<?php echo (Auth::user()->reset_session=='1'?'true':'false'); ?>">
                       Enabled
                    </label>
                  </div>
                  <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="radio" ng-model="disabled" value='disabled' ng-checked="<?php echo (Auth::user()->reset_session=='0'?'true':'false'); ?>">
                        Disabled
                      </label>
                  </div>
                  <div class="form-group row">
                    <div class="col-lg-6">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
                </div>
            </div>
          </form>-->
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
     function submit(resetSession){
      var obj = {
      enabled: $scope.enabled,
      disabled:$scope.disabled
      }
      console.log(obj)
      $http.post('/users/'+{{Auth::user()->id}}+'/reset-session',obj)
        .then(data => {
          sweetAlert('Success', 'Success', 'success')
           setTimeout(function () {
               location.href = window.location
            }, 2000)
        })

     }

      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
