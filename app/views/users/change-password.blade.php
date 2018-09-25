<?php use Acme\Auth\Auth;?>
@extends('layouts.master')
@section('content')
  <div ng-controller="ChangePasswordCtrl" class="head-weight">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">Change password</div>
          <div class="panel-body">
              <div id="profile" class="tab-pane active">
                <div class="col-md-6 col-md-offset-3">
                      <form name="changePasswordFrm" ng-submit="changePassword(pwdObj)" style="border-radius: 0px;" class="form-horizontal group-border-dashed">
                        <div class="form-group">
                          <label>Old password</label>
                          <input name="old_password" ng-model="pwdObj.old_password" type="password" required class="form-control" placeholder="Password">
                        </div>
                        <div class="form-group">
                          <label>New password</label>
                          <input name="password" ng-model="pwdObj.password" type="password" required class="form-control" placeholder="Password">
                        </div>
                        <div class="form-group">
                          <label>Password confirmation</label>
                          <input name="password_confirmation" ng-model="pwdObj.password_confirmation" required type="password" class="form-control" placeholder="Password">
                        </div>
                        <button class="btn btn-lg btn-primary m-offset-l-15" type="submit">Change password</button>
                      </form>
                    </div>
              </div><!--
            <ul class="nav nav-tabs">
               <li class="active"><a href="#profile" data-toggle="tab" aria-expanded="true">Profile</a></li>
              <li class=""><a href="#edit" data-toggle="tab" aria-expanded="false">Edit</a></li> 
            </ul>
            <div class="tab-content">
              
            </div>-->
          </div>
        </div>
      </div>
    </div>
  </div>
@stop

@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('ChangePasswordCtrl', ['$scope', '$http', function ($scope, $http) {
      $scope.changePassword = changePassword

//console.log()
      function changePassword (obj) {
        if ($scope.changePasswordFrm.$invalid) {
          alert('Please fill all the details correctly')
          return
        }
        if (obj.password != obj.password_confirmation) {
          alert('Password and password confirmation do not match')
          return
        }
        $http.post('/api/v1/users/'+{{Auth::user()->id}}+'/actions/change-password', obj)
          .then(function (data) {
            sweetAlert('Success', 'Password changed successfully', 'success')
            setTimeout(function () {
                window.location.href = '/logout'
            }, 1500)

          }, function (err) {
            if (err.data.code && err.data.code == 1) {
              sweetAlert('Error', 'Fill all details', 'error')
              return
            }
            if (err.data.code && err.data.code == 2) {
              sweetAlert('Error', 'Old password is incorrect. Please try again.', 'error')
              return
            }
            if (err.data.code && err.data.code == 3) {
              sweetAlert('Error', 'Password and password confirmation do not match.', 'error')
              return
            }
            if (err.status == 403) {
              sweetAlert('Unauthorized', 'Not you\'re profile', 'error')
              return
            }
          })
      }

      function fail (err) {
       
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
