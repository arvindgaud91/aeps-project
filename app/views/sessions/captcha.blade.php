<?php use Acme\Auth\Auth; ?>
<!DOCTYPE html>
<html lang="en" ng-app="DIPApp">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.ico">
    <title>Digital India Payments </title>
    <link rel="stylesheet" href="/sweetalert/sweetalert.css">
    <link rel="stylesheet" type="text/css" href="/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
    <link rel="stylesheet" type="text/css" href="/material-design-icons/css/material-design-iconic-font.min.css"/>
    <link rel="stylesheet" href="/css/style.css" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <style>
      .cur { cursor: pointer; }
    </style>
  </head>
  <body class="be-splash-screen">
    <!-- Header logo sidebar -->
    <nav class="navbar navbar-default navbar-fixed-top be-top-header" style="height: 100px; background: whitesmoke;">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <br>
            <div class="col-md-2">
              <?php
              $domain_data = preg_replace('#^https?://#', '', Request::root());
              ?>
              @if($domain_data == 'am-tech.digitalindiapayments.com')

               <img src="/images/AM-Tech.png" height="50px;" style="margin-left: -40px;">
              @else
                <img src="/images/cinqueterre.png" height="50px;" style="margin-left: -40px;">
              @endif
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-2">
            </div>
            <div class="col-md-2">
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-2">
              <img src="/images/rbl.png" height="50px;">
            </div>
          </div>
        </div>
      </div>
    </nav>
    <!-- End header sidebar -->
    <div ng-controller="LoginCtrl" class="col-md-4 col-md-offset-4 head-weight">
      <div class="be-wrapper be-login">
        <div class="be-content">
          <div class="main-content container-fluid">
            <div class="splash-container" style="margin: 116px auto;">
              <div class="panel panel-default panel-border-color panel-border-color-primary" >
                
                <div class="panel-body">
                  <form name="loginFrm" ng-show="frmState == 0" novalidate ng-submit="login(user)">
                    
                    <div class="form-group">
                    <!-- <div class="g-recaptcha" data-sitekey="6LdjxTcUAAAAAGDrD2Ia04SlV5_-xoNTaWLW3pJq"></div> -->
                    <div vc-recaptcha key="'6LdjxTcUAAAAAGDrD2Ia04SlV5_-xoNTaWLW3pJq'"></div>
                  </div>
                    
                  </form>
                  
                </div>
              </div>
              <!-- Can Add Signup Link -->
            </div>
            <div class="footer" align="center" style="margin-top: -80px;">
              <!--<img src="images/digital.png" height="50px;">-->
              <!--<img src="images/digital.png" height="50px;">-->
          @if($domain_data == 'am-tech.digitalindiapayments.com')

              &nbsp;&nbsp;&nbsp;&nbsp;<img src="/images/adhar.png" height="50px;"> <br>
              <h6>Am-Tech.</h6>
@else
 &nbsp;&nbsp;&nbsp;&nbsp;<img src="/images/adhar.png" height="50px;"> <br>
<h6>Digital India Payments Limited. Copyright <?php echo date("Y"); ?>.</h6>
@endif
            </div>
          </div>
        </div>
      </div>
      <!-- <form ng-submit="login(user)">
        <div class="form-group">
          <label>Email</label>
          <input type="text" name="email" ng-model="user.email" required class="form-control">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" ng-model="user.password" required class="form-control">
        </div>
        <button class="btn btn-primary" type="submit">Login</button>
        </form>

        <div id="OTPModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Verify OTP</h4>
            </div>
            <div class="modal-body row">
              <div class="col-md-12">
                <form ng-submit="otpVerification(otpObj)" name="OTPFrm">
                  <div class="form-group">
                    <label>OTP</label>
                    <input required ng-model="otpObj.otp" type="text" class="form-control">
                  </div>
                  <input class="btn btn-primary" type="submit" value="Verify mobile number">
                </form>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
        </div> -->
    </div>
    <script src="/sweetalert/sweetalert.min.js"></script>
    <script src="/components/jquery/dist/jquery.min.js"></script>
    <script src="/components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/components/angular/angular.min.js"></script>
    <script src="/components/angular/angular-recaptcha.min.js"></script>
    <script src="/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script> 
    <script>
      angular.module('DIPApp', ['vcRecaptcha'])
        .controller('LoginCtrl', ['$scope','vcRecaptchaService', '$http', function ($scope,vcRecaptchaService, $http) {
          var vm = this;
        vm.publicKey = "6LdjxTcUAAAAAGDrD2Ia04SlV5_-xoNTaWLW3pJq";
        
          //window.s = $scope
          $scope.frmState = 0
          $scope.login = login
          $scope.forgotPassword = forgotPassword
          $scope.getOTP = getOTP
          $scope.getSessionResetOTP = getSessionResetOTP
          $scope.passwordReset = passwordReset
          $scope.sessionReset = sessionReset
          $scope.otpVerification = otpVerification


          function login (user) {
            if (! user.phone_no || ! user.password) {
              sweetAlert('Incomplete', 'Please fill all details.', 'error')
              return
            }
            if(vcRecaptchaService.getResponse() === ""){ //if string is empty
                sweetAlert('Incomplete', 'Please click on captcha and submit!', 'error')
                return
            }
            //alert(vcRecaptchaService.getResponse())
            user['g-recaptcha-response'] =vcRecaptchaService.getResponse() 
            $http.post('/login', user)
              .then(function (data) {
                sweetAlert('Success', 'Successfully logged in.', 'success')
                setTimeout(function () {
                  window.location.href = '/services'
                }, 1500)
              }, function (err) {
                if (err.data.code && err.data.code == 2) {
                  sweetAlert('Email verification pending', 'Please verify you\'re email to log in.', 'error')
                  return
                }
                if (err.data.code && err.data.code == 3) {
                  sweetAlert('Phone number verification pending', 'Please verify you\'re mobile number to log in.', 'error')
                  $scope.otpObj = {email: err.data.email}
                  showOTPModal(err.data)
                  return
                }
                if (err.data.code && err.data.code == 8) {
                  sweetAlert('Failed', 'Please check captcha', 'error')
                 
                  return
                }
                if (err.data.code && err.data.code == 9) {
                  sweetAlert('Failed', 'Captch verification failed', 'error')
                 
                  return
                }
                if (err.data.code && err.data.code == 6) {
                  $scope.frmState = 3
                  $scope.sessionOtpObj = {phone_no: user.phone_no}
                  return
                }
                sweetAlert('Invalid credentials', 'Email & password do not match.', 'error')
              })
          }
          function forgotPassword () {
            $scope.frmState = 1
          }
          function getOTP (obj) {
            if ($scope.passwordResetFrm.$invalid) {
              sweetAlert('Error', 'Please fill the phone number', 'warning')
              return
            }
            $http.post('/api/v1/actions/password-reset-otp', obj)
              .then(function () {
                $scope.frmState = 2
                $scope.passwordResetObj = { phone_no: obj.phone_no }
              }, function (err) {
                if (err.data.code == 1) {
                  sweetAlert('Error', 'Phone number is not registered with us', 'error')
                  return
                }
                sweetAlert('Error', 'Something went wrong. Try again later.', 'error')
              })
          }
          function getSessionResetOTP (obj) {
            if ($scope.sessionResetFrm.$invalid) {
              sweetAlert('Error', 'Please fill the phone number', 'warning')
              return
            }
            $http.post('/api/v1/actions/session-reset-otp', obj)
              .then(function () {
                $scope.frmState = 4
                $scope.sessionResetObj = { phone_no: obj.phone_no }
              }, function (err) {
                if (err.data.code == 1) {
                  sweetAlert('Error', 'Phone number is not registered with us', 'error')
                  return
                }
                sweetAlert('Error', 'Something went wrong. Try again later.', 'error')
              })
          }
          function passwordReset (obj) {
            if ($scope.newPasswordFrm.$invalid) {
              sweetAlert('Error', 'Please fill all the fields', 'warning')
              return
            }
            if (obj.password != obj.password_confirmation) {
              sweetAlert('Error', 'Password and password confirmation do not match', 'warning')
              return
            }
            $http.post('/api/v1/actions/new-password-otp', obj)
              .then(function () {
                sweetAlert('Success', 'Password changed successfully', 'success')
                window.location.href = '/services'
              }, function (err) {
                if (err.data.code && err.data.code == 1) {
                  sweetAlert('Error', 'Invalid OTP or OTP expired.', 'error')
                  return
                }
                if (err.data.code && err.data.code == 2) {
                  sweetAlert('Error', 'Password successfully changfed, but login with bank failed.', 'error')
                  return
                }
                sweetAlert('Error', 'Try again later.', 'error')
              })
          }
          function sessionReset (obj) {
            if ($scope.newSessionFrm.$invalid) {
              sweetAlert('Error', 'Please fill all the fields', 'warning')
              return
            }
    
            $http.post('/api/v1/actions/new-session-otp', obj)
              .then(function () {
                sweetAlert('Success', 'Logged in', 'success')
                window.location.href = '/services'
              }, function (err) {
                if (err.data.code && err.data.code == 1) {
                  sweetAlert('Error', 'Invalid OTP or OTP expired.', 'error')
                  return
                }
                sweetAlert('Error', 'Try again later.', 'error')
              })
          }
          function otpVerification (obj) {
            if ($scope.OTPFrm.$invalid) {
              toastr.error('Fill all the details.')
              return
            }
            $http.post('/verification/phone/'+obj.otp, obj)
              .then(function (data) {
                sweetAlert('Success', 'Mobile no. is verified. Please login to continue.', 'success')
                $('#OTPModal').modal('hide')
              }, function (err) {
                if (err.status == 400) {
                  sweetAlert('Error', 'Please send email', 'error')
                  return
                }
                sweetAlert('Error', 'OTP entered is invalid', 'error')
              })
          }

          function showOTPModal (obj) {
            $('#OTPModal').modal('show')
          }

          function fail (err) {
            
            sweetAlert('Error', 'Something went wrong', 'error')
          }
        }])
    </script>
    <!-- <script src="assets/js/main.js" type="text/javascript"></script> -->
    </script>
  </body>
</html>
