<?php use Acme\Auth\Auth; ?>
<!DOCTYPE html>
<html lang="en" ng-app="DIPApp">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <?php
      $domain_data = preg_replace('#^https?://#', '', Request::root());
    ?>
    @if($domain_data == 'am-tech.digitalindiapayments.com')
        <link rel="shortcut icon" href="images/_blank.png">
        <title>AM-TECH</title>
    @elseif($domain_data == 'service.aepsmoney.com')
        <link rel="shortcut icon" href="images/_blank.png">
        <title>AMIABLE COMTRADE AND ONLINE SHOP</title>
    @elseif($domain_data == 'aeps.unnayon.in')
        <link rel="shortcut icon" href="images/_blank.png">
        <title>UNNAYON CONSULTANCY SERVICES PVT LTD</title>
    @elseif($domain_data == 'aeps.hamarakendra.com')
        <link rel="shortcut icon" href="images/_blank.png">
        <title>Hamara Kendra - a chain of e Governance Services Centres</title>
    @elseif($domain_data == 'aeps-rl.hamarakendra.com')
        <link rel="shortcut icon" href="images/_blank.png">
        <title>Hamara Kendra - a chain of e Governance Services Centres</title>
    @elseif($domain_data == 'payments.digitalworldpaymentshub.com')
        <link rel="shortcut icon" href="images/_blank.png">
        <title>DIGITAL WORLD PAYMENTS HUB</title>
    @elseif($domain_data == 'aeps.acospay.com')
        <link rel="shortcut icon" href="images/_blank.png">
        <title>AMIABLE COMTRADE AND ONLINE SHOP</title>
    @elseif($domain_data == 'aeps.himveda.co.in')
        <link rel="shortcut icon" href="images/_blank.png">
        <title>Himveda E-Solution Pvt.Ltd.</title>
    @elseif($domain_data == 'wallet.reijiro.co.in')
        <link rel="shortcut icon" href="images/_blank.png">
        <title>Reijiro</title>
    @elseif($domain_data == 'rb.myam-tech.com')
        <link rel="shortcut icon" href="images/_blank.png">
        <title>:: My AM Tech ::</title>
     @else
        <link rel="shortcut icon" href="favicon.ico">
        <title>Digital India Payments </title>
    @endif

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--<link rel="shortcut icon" type="image/ico" href="favicon.ico" />-->

    <!-- Vendor styles -->
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.css" />
    <link rel="stylesheet" href="vendor/metisMenu/dist/metisMenu.css" />
    <link rel="stylesheet" href="vendor/animate.css/animate.css" />
    <link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.css" />

    <!-- App styles -->
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="/sweetalert/sweetalert.css">
    <link rel="stylesheet" type="text/css" href="/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
    <link rel="stylesheet" type="text/css" href="/material-design-icons/css/material-design-iconic-font.min.css"/>
    

</head>
<body class="blank">
<div class="color-line"></div>

<!-- <div class="back-link">
    <a href="index.html" class="btn btn-primary">Back to Dashboard</a>
</div> --> 
<?php
$domain_data = preg_replace('#^https?://#', '', Request::root());
?>
<div class="col-sm-12">
@if($domain_data == 'am-tech.digitalindiapayments.com')
 <img src="/images/AM-Tech.png"  height="60px;" class="pull-left" style="margin-top: 12px;">
@elseif($domain_data == 'service.aepsmoney.com')
  <img src="/images/ACOSPAY2.png"  height="60px;" class="pull-left" style="margin-top: 12px;">
@elseif($domain_data == 'aeps.unnayon.in')
  <img src="/images/UNNAYONLOGO.jpg"  height="60px;" class="pull-left" style="margin-top: 12px;">
@elseif($domain_data == 'aeps.hamarakendra.com')
  <img src="/images/hk_logo.jpg"  height="60px;" class="pull-left" style="margin-top: 12px;">
@elseif($domain_data == 'aeps-rl.hamarakendra.com')
  <img src="/images/hk_logo.jpg"  height="60px;" class="pull-left" style="margin-top: 12px;">
@elseif($domain_data == 'aeps.unnayon.in')
  <img src="/images/UNNAYONLOGO.jpg"  height="60px;" class="pull-left" style="margin-top: 12px;">
@elseif($domain_data == 'aeps.hamarakendra.com')
  <img src="/images/hk_logo.jpg"  height="60px;" class="pull-left" style="margin-top: 12px;">
@elseif($domain_data == 'aeps-rl.hamarakendra.com')
  <img src="/images/hk_logo.jpg"  height="60px;" class="pull-left" style="margin-top: 12px;">
@elseif($domain_data == 'payments.digitalworldpaymentshub.com')
  <img src="/images/digitalworld.png"  height="60px;" class="pull-left" style="margin-top: 12px;">
@elseif($domain_data == 'aeps.acospay.com')
  <img src="/images/ACOSPAY.png"  height="60px;" class="pull-left" style="margin-top: 12px;">
@elseif($domain_data == 'aeps.himveda.co.in')
  <img src="/images/himveda.jpg"  height="60px;" class="pull-left" style="margin-top: 12px;">
@elseif($domain_data == 'wallet.reijiro.co.in')
  <img src="/images/reijirologopng.png"  height="60px;" class="pull-left" style="margin-top: 12px;">
@elseif($domain_data == 'rb.myam-tech.com')
  <img src="/images/"  height="60px;" class="pull-left" style="margin-top: 12px;">
@else
<img src="/images/cinqueterre.png"  height="60px;" class="pull-left" style="margin-top: 12px;">
@endif
<img src="/images/rbl.png" height="60px;" class="pull-right" style="margin-top: 12px;">
</div>
<div ng-controller="LoginCtrl" class="login-container">

    <div class="row">
        <div class="col-md-12">
            <div class="text-center m-b-md" >
                 <h3>User Login</h3> 
                 <!-- <h3>We Would Be Offline from 10 to 11 pm (17/01/2018) Due To Maintenance Activity</h3>  -->
            </div>
            <div class="hpanel">
                <div class="panel-body">
                        <form name="loginFrm" ng-show="frmState == 0" novalidate ng-submit="login(user)">
                            <div class="form-group">
                                <label class="control-label" for="username">Email & Mobile Number</label>
                                <input type="text" id="phone_no" ng-model="user.phone_no"  name="phone_no" placeholder="Mobile Number" class="form-control" isnumber required >
                                
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="password">Password</label>
                                <input id="password" ng-model="user.password" type="password" placeholder="******" name="password" placeholder="Password" class="form-control" required >
                                
                            </div>

                            <div class="form-group" style="margin: 0;">

                              <div class="row">
                                <div class="col-sm-6">
                                  <input type="text" id="txtCompare" ng-model="user.captcha"  name="captcha" placeholder="Please enter captcha" class="form-control"required/>
                                </div>
                                <div class="col-sm-3 col-xs-6" type="text" id="txtCaptcha" style="background: gainsboro;text-align: center;border: none;font-weight: bold;font-size: 20px;font-style: italic;height: 33px;" /></div>
                                <button type="button" id="btnrefresh" class="col-sm-2 col-xs-6 btn btn-info btn-sm" onclick="GenerateCaptcha();" style="border-radius: 0px;height: 33px;" /><span class="glyphicon glyphicon-refresh"></span></button>
                              </div>
                              <!-- <input id="btnValid" type="button" value="Check" onclick="alert(ValidCaptcha());" /> -->
                              
                                <!-- <div style="margin: 0 auto;display: table" vc-recaptcha key="'6LdjxTcUAAAAAGDrD2Ia04SlV5_-xoNTaWLW3pJq'"></div-->
                            </div> 
                             <div class="checkbox">
                                <!-- <input type="checkbox" class="i-checks" checked>
                                     Remember login -->
                                 @if($domain_data == 'aeps.unnayon.in' || $domain_data == 'aeps.acospay.com' || $domain_data == 'aeps.himveda.co.in' || $domain_data=='wallet.reijiro.co.in' || $domain_data == 'aeps-rl.hamarakendra.com' || $domain_data == 'aeps.hamarakendra.com')
                                  <div class="help-block small pull-right"></div>
                                 @else
                                  <div style="margin: 0;" class="help-block small pull-right"> <a ng-click="forgotPassword()">Forgot Password?</a></div>
                                @endif
                                <div class="clearfix"></div>
                            </div>
                           <div class="form-group login-submit">
                                <button type="submit" name="login" class="btn btn-success btn-block" >LOGIN</button>
                            </div>
                        </form>
                        <form name="passwordResetFrm" ng-show="frmState == 1" ng-submit="getOTP(otpObj)">
                    <div class="form-group">
                      <input type="text" ng-model="otpObj.phone_no"  name="phone_no" placeholder="Mobile Number" class="form-control" isnumber required>
                    </div>
                            @if($domain_data == 'aeps.unnayon.in' || $domain_data == 'aeps.acospay.com' || $domain_data == 'aeps.himveda.co.in'  || $domain_data=='wallet.reijiro.co.in' || $domain_data == 'aeps-rl.hamarakendra.com' || $domain_data == 'aeps.hamarakendra.com')
                                     <p class="help-block small pull-right"></p>
                                     @else
                                
                    <div class="form-group login-submit">
                      <button type="submit" class="btn btn-primary btn-xl">Send OTP</button>
                    </div>
                                @endif





                  </form>
                  <form name="newPasswordFrm" ng-show="frmState == 2" ng-submit="passwordReset(passwordResetObj)">
                    <div class="form-group">
                      <input ng-model="passwordResetObj.otp" type="text" name="otp" placeholder="OTP" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <input ng-model="passwordResetObj.password" type="password" name="password" placeholder="Password" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <input ng-model="passwordResetObj.password_confirmation" type="text" name="password_confirmation" placeholder="Password Confirmation" class="form-control" required>
                    </div>
                    <div class="form-group login-submit">
                      <button type="submit" class="btn btn-primary btn-xl">Reset password</button>
                    </div>
                  </form>

                                  
                                
                    <form name="sessionResetFrm" ng-show="frmState == 3" ng-submit="getSessionResetOTP(sessionOtpObj)">
                    <h4>User is already logged in on another device.</h4>
                    <h4>Reset Session?</h4>
                    <div class="form-group">
                      <input type="text" ng-model="sessionOtpObj.phone_no"  name="phone_no" placeholder="Mobile Number" class="form-control" isnumber required disabled>
                    </div>
                    @if($domain_data == 'aeps.unnayon.in' || $domain_data == 'aeps.acospay.com' || $domain_data == 'aeps.himveda.co.in'  || $domain_data=='wallet.reijiro.co.in' || $domain_data == 'aeps-rl.hamarakendra.com' || $domain_data == 'aeps.hamarakendra.com')
                                     <p class="help-block small pull-right"></p>
                                     @else
                    <div class="col-md-3">
                      <button type="submit" class="btn btn-primary btn-xl">Send OTP</button>
                    </div>
                    
                  
                                @endif

                 </form>
                  <form name="resetSessionFrm" ng-show="frmState == 3" ng-submit="getResetSession(resetSessionObj)">
                    
                     
                    <div class="col-md-3">

                      <button type="submit" class="btn btn-primary btn-xl" style ="margin-left:10px">Reset Session</button>
                    </div>
                  </form>
                   
                  <form name="newSessionFrm" ng-show="frmState == 4" ng-submit="sessionReset(sessionResetObj)">
                    <div class="form-group">
                      <input ng-model="sessionResetObj.otp" type="text" name="otp" placeholder="OTP" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <input ng-model="sessionResetObj.password" type="password" name="password" placeholder="Password" class="form-control" required>
                    </div>
                    <div class="form-group login-submit">
                      <button type="submit" class="btn btn-primary btn-xl">Reset Session</button>
                    </div>
                  </form>
                  <form name="newSessionFrm" ng-show="frmState == 5" ng-submit="logoutUser(userMobile)">
                     <button type="submit" class="btn btn-primary btn-block">Logout</button>
                  </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row"> 
        &nbsp;&nbsp;&nbsp;&nbsp;<img src="http://dmt-test.digitalindiapayments.com:8000/images/adhar.png" height="50px;" style="margin-left: 169px;">
        <?php $domain_data = preg_replace('#^https?://#', '', Request::root()); ?>
        @if($domain_data == 'am-tech.digitalindiapayments.com')
            <div class="col-md-12 text-center">
                &copy; Am-Tech
            </div>
        @elseif($domain_data == 'service.aepsmoney.com')
            <div class="col-md-12 text-center">
                &copy; AMIABLE COMTRADE AND ONLINE SHOP PVT.LTD
            </div>
        @elseif($domain_data == 'aeps.unnayon.in')
            <div class="col-md-12 text-center">
                &copy; UNNAYON CONSULTANCY SERVICES PVT LTD
            </div>
        @elseif($domain_data == 'aeps.hamarakendra.com')
            <div class="col-md-12 text-center">
                &copy; IPS e Services Pvt. Ltd.
            </div>
        @elseif($domain_data == 'aeps-rl.hamarakendra.com')
            <div class="col-md-12 text-center">
                &copy; IPS e Services Pvt. Ltd.
            </div>
        @elseif($domain_data == 'payments.digitalworldpaymentshub.com')
            <div class="col-md-12 text-center">
                &copy; DIGITAL WORLD PAYMENTS HUB
            </div>
        @elseif($domain_data == 'aeps.acospay.com')
            <div class="col-md-12 text-center">
                &copy;  AMIABLE COMTRADE AND ONLINE SHOP PVT.LTD
            </div>
        @elseif($domain_data == 'aeps.himveda.co.in')
            <div class="col-md-12 text-center">
                &copy; Himveda E-Solution Pvt.Ltd.
            </div>
        @elseif($domain_data == 'wallet.reijiro.co.in')
            <div class="col-md-12 text-center">
                &copy; Reijiro
            </div>
        @elseif($domain_data == 'rb.myam-tech.com')
            <div class="col-md-12 text-center">
                &copy; MY AM TECH
            </div>
        @else
            <div class="col-md-12 text-center">
                Digital India Payments Limited. Copyright <?php echo date("Y"); ?>.
            </div>
        @endif
    </div>
</div>

<!-- Vendor scripts -->
<script src="vendor/jquery/dist/jquery.min.js"></script>
<script src="vendor/jquery-ui/jquery-ui.min.js"></script>
<script src="/sweetalert/sweetalert.min.js"></script>
<script src="/components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/components/angular/angular.min.js"></script>
<script src="/components/angular/angular-recaptcha.min.js"></script>
<script src="/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<script src='https://www.google.com/recaptcha/api.js'></script> 
<script src="vendor/jquery/dist/jquery.min.js"></script>
<script src="vendor/slimScroll/jquery.slimscroll.min.js"></script>
<script src="vendor/metisMenu/dist/metisMenu.min.js"></script>
<script src="vendor/iCheck/icheck.min.js"></script>
<script src="vendor/sparkline/index.js"></script>
<!-- App scripts -->

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
          $scope.getResetSession =getResetSession
          $scope.logoutUser = logoutUser

          function login (user) {
            var str1 = removeSpaces($('#txtCaptcha').html());
            var str2 = removeSpaces(document.getElementById('txtCompare').value);
            if (str1 != str2){
                sweetAlert('Incomplete', 'Captcha is not valid', 'error')
              return
            }

            if (! user.phone_no || ! user.password) {
              sweetAlert('Incomplete', 'Please fill all details.', 'error')
              return
            }
          
            $http.post('/login', user)
              .then(function (data) {
                console.log(data)
                if(data.data.status == 1)
                {
                   sweetAlert('Success', 'Successfully logged in.', 'success')
                setTimeout(function () {
                  window.location.href = '/services'
                }, 1500)
              }else
              {
                 if(data.data.loginstatus == 1)
                {
                  sweetAlert('Error', data.data.desc, 'error')
                  $scope.frmState = 5
                  $scope.userMobile=user.phone_no

                }else{
                  sweetAlert('Invalid credentials', 'Email & password do not match.', 'error')
                }
                

              // setTimeout(function () {
              //     window.location.href = '/login'
              //   }, 1500)
             

              }
               
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
                //console.log(err)
                // if (err.data.code == 1) {
                //   sweetAlert('Error', 'Phone number is not registered with us', 'error')
                //   return
                // }
                sweetAlert('Error', err.data, 'error')
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
                // if (err.data.code && err.data.code == 1) {
                //   sweetAlert('Error', 'Invalid OTP or OTP expired.', 'error')
                //   return
                // }
                // if (err.data.code && err.data.code == 2) {
                //   sweetAlert('Error', 'Password successfully changfed, but login with bank failed.', 'error')
                //   return
                // }
                // sweetAlert('Error', 'Try again later.', 'error')
                window.location.href = '/'
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

          function logoutUser (obj) {

             //$scope.logOutMobile = 
            
            $http.post('/logout', { mobilenumber: obj })
              .then(function () {
                sweetAlert('Success', 'Logout successfully', 'success')
                window.location.href = '/login'
              }, function (err) {
                
                sweetAlert('Error', 'Try again later.', 'error')
              })
          }
          function showOTPModal (obj) {
            $('#OTPModal').modal('show')
          }

          function fail (err) {
            
            sweetAlert('Error', 'Something went wrong', 'error')
          }

          function getResetSession (phone_no){
            $http.post('/api/v1/actions/reset-session/'+$scope.sessionOtpObj.phone_no, $scope.sessionOtpObj.phone_no)
              .then(function () {
                $scope.frmState = 3
                $scope.resetSessionObj = { phone_no: $scope.sessionOtpObj.phone_no }
                sweetAlert('Success', 'Session Reset Successfully', 'success')
                window.location.href = '/login'
              }, function (err) {
                if (err.status==400) {
                  sweetAlert('Error', 'You do not have access. Please contact admin', 'error')
                  return
                }
                sweetAlert('Error', 'Try again later.', 'error')
              })
          }
        }])
    </script>
   <script type="text/javascript">
    $(document).ready(function(){
        GenerateCaptcha();
    });
    /* Function to Generat Captcha */
    function GenerateCaptcha() {
    var chr1 = Math.ceil(Math.random() * 9)+ '';
    var chr2 = Math.ceil(Math.random() * 9)+ '';
    var chr3 = Math.ceil(Math.random() * 9)+ '';

    var captchaCode = chr1 + ' ' + chr2 + ' ' + chr3;
    $("#txtCaptcha").html(captchaCode);
    }
    /* Validating Captcha Function */
   
    /* Remove spaces from Captcha Code */
    function removeSpaces(string) {
    return string.split(' ').join('');
    }
  </script>
</body>
</html>