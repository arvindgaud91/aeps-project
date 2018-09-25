<?php use Acme\Auth\Auth;
  $user = Auth::user();
  $dis=Auth::distributordetails($user->vendor->parent_id);
  //dd($dis);
?>
<!DOCTYPE html>
<html ng-app="DIPApp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
      $domain_data = preg_replace('#^https?://#', '', Request::root());
    ?>

    @if($domain_data == 'am-tech.digitalindiapayments.com')
        <link rel="shortcut icon">
        <title>AM-TECH</title>
      @elseif($domain_data == 'service.aepsmoney.com')
        <link rel="shortcut icon">
        <title>AMIABLE COMTRADE AND ONLINE SHOP</title>
      @elseif($domain_data == 'aeps.unnayon.in')
        <link rel="shortcut icon">
        <title>UNNAYON CONSULTANCY SERVICES PVT LTD</title>
      @elseif($domain_data == 'aeps.hamarakendra.com')
        <link rel="shortcut icon">
        <title>Hamara Kendra - a chain of e Governance Services Centres</title>
      @elseif($domain_data == 'aeps.acospay.com')
        <link rel="shortcut icon">
        <title>AMIABLE COMTRADE AND ONLINE SHOP</title>
      @elseif($domain_data == 'aeps.himveda.co.in')
        <link rel="shortcut icon">
        <title>Himveda E-Solution Pvt.Ltd.</title>
      @elseif($domain_data == 'wallet.reijiro.co.in')
        <link rel="shortcut icon">
        <title>Reijiro</title>
      @elseif($domain_data == 'aeps-rl.hamarakendra.com')
        <link rel="shortcut icon">
        <title>Hamara Kendra - a chain of e Governance Services Centres</title>
      @elseif($domain_data == 'rb.myam-tech.com')
        <link rel="shortcut icon">
        <title>:: My AM Tech ::</title>
      @else
        <link rel="shortcut icon" href="favicon.ico">
        <title>Digital India Payments </title>
      @endif
    <link rel="stylesheet" href="/components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/sweetalert/sweetalert.css">
    <link rel="stylesheet" href="/components/toastr/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
    <link rel="stylesheet" type="text/css" href="/material-design-icons/css/material-design-iconic-font.min.css"/>
      <link rel="stylesheet" href="/vendor/metisMenu/dist/metisMenu.css" />
      <link rel="stylesheet" href="/vendor/animate.css/animate.css" />
      <link rel="stylesheet" href="/css/style-new.css" type="text/css"/>
      <link rel="stylesheet" href="/isteven-angular-multiselect/isteven-multi-select.css">
      <link rel="stylesheet" href="/css/esscale.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
      <link rel="stylesheet"  href="/vendor/datatables.net-bs/css/dataTables.bootstrap.min.css" />
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css" />
      <link rel="stylesheet" href="/isteven-angular-multiselect/isteven-multi-select.css">
      <!-- Vendor styles -->
    <link rel="stylesheet" href="/vendor/fontawesome/css/font-awesome.css" />
    <link rel="stylesheet" href="/vendor/metisMenu/dist/metisMenu.css" />
    <link rel="stylesheet" href="/vendor/animate.css/animate.css" />
    <link rel="stylesheet" href="/vendor/bootstrap/dist/css/bootstrap.css" />
    <link rel="stylesheet"  href="http://localhost:8000/vendor/datatables.net-bs/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css" /> 
    <!-- App styles -->
    <link rel="stylesheet" href="/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" type='text/css'>
    <link rel="stylesheet" href="/fonts/pe-icon-7-stroke/css/helper.css" type='text/css'>
    <link rel="stylesheet" href="/styles/style.css">
    <link rel="stylesheet" href="/styles/esscale.css">
    <link rel="stylesheet" href="/isteven-angular-multiselect/isteven-multi-select.css">
    <link rel="stylesheet" href="/vendor/c3/c3.min.css" />
    <link rel="stylesheet" href="/vendor/ladda/dist/ladda-themeless.min.css" />
    <style>
      [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
        display: none !important;
      }
      .error-p, .err-mark {
        color: red;
      }
      body.fixed-header,
        body.scrollable-block {
            padding: 70px 20px;
        }

        body.scrollable-block {
            min-width: 1000px;
        }

        body.fixed-header .header,
        body.scrollable-block .header {
            position: fixed;
            top: 0;
            color: #FFC234;
            width: 100%;
            z-index: 1000;
            margin: 0 -20px;
            padding: 0 20px;
            border-bottom: 10px solid red;
        }
        #logo { padding: 4px 0 0; }
        .error_controller {
            width: 506px;
            margin: auto;
        }
        .b {
            font-weight: 600;
        }
        .font18, .font18 * {
            font-size: 17px;
            line-height: 22px;
        }
        ol, ul {
          list-style: none;
          margin: 0;
          padding: 0;
      }
      ul li{list-style-type: none;}
      .err_img{margin-top: 60px;}
  </style>
  </head>
  <body ng-controller="MainCtrl" ng-cloak class="fixed-navbar sidebar-scroll ng-cloak">
        <div id="header" style="background: #f7f9fa;">
            <div class="color-line">
            </div>
            <div id="logo" class="light-version">
                <span>
                   <a href="/"><img src="/images/cinqueterre.png" alt="DIPL Logo" /></a>
                </span>
            </div>
        </div>

        <div id="wrapper1">
            <div class="content1">
                <div class="container">
                  <div class="error_controller">
                      <div class="err_img"><img src="/images/error_image.png" alt="" title=""></div>
                      <div class="fl">
                        <div class="pad15">
                          <div class="txt font18">
                            <b class="b">Payment failed due to any of these reasons:</b><br>
                               <ul class="aln">
                                   <li>Session expired due to inactivity</li>
                                   <li>Our system encountered an obstacle</li>
                               </ul>
                          </div>
                          <div class="txt font18"><br>
                            <b class="b">You can fix it yourself! Here's how:</b><br>
                              <ul class="aln">
                                  <li>Clear cookies &amp; temporary internet files of the browser</li>
                                  <li>Check payment status with your bank to avoid double payment</li>
                                  <li>Launch a new browser &amp; start from the beginning</li>
                                  <li>Still unable to transact? visit us at <a href="https://www.paytm.com/care" class="blue-text">paytm.com/care</a></li>
                              </ul>
                          </div>
                        </div>
                      </div>
                  </div>
                  
                </div>
            </div>
        </div>
      
    <!--    </div> -->
    <script src="/sweetalert/sweetalert.min.js"></script>
    <script src="/components/jquery/dist/jquery.min.js"></script>
    <script src="/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
    <script src="/components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/components/toastr/toastr.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="/components/angular/angular.min.js"></script>
    <script src="/jquery-flot/jquery.flot.js" type="text/javascript"></script>
    <script src="/jquery-flot/jquery.flot.pie.js" type="text/javascript"></script>
    <script src="/jquery-flot/jquery.flot.resize.js" type="text/javascript"></script>
    <script src="/jquery-flot/plugins/jquery.flot.orderBars.js" type="text/javascript"></script>
    <script src="/jquery-flot/plugins/curvedLines.js" type="text/javascript"></script>
    <script src="/jquery.sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <script src="/countup/countUp.min.js" type="text/javascript"></script>
    <script src="/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/jqvmap/jquery.vmap.min.js" type="text/javascript"></script>
    <script src="/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
    <script src="/jquery.maskedinput/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/isteven-angular-multiselect/isteven-multi-select.js"></script>
    <script type="text/javascript" src="/x2js/xml2json.js"></script>
    <script src="/js/ng-file-upload-shim.js"></script>  
    <script src="/js/ng-file-upload-shim.min.js"></script>  
    <script src="/js/ng-file-upload.min.js"></script>
    <script src="/vendor/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="/vendor/metisMenu/dist/metisMenu.min.js"></script>
    <script src="/vendor/iCheck/icheck.min.js"></script>
    <script src="/vendor/sparkline/index.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="/vendor/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- DataTables buttons scripts -->
    <script src="/vendor/pdfmake/build/pdfmake.min.js"></script>
    <script src="/vendor/pdfmake/build/vfs_fonts.js"></script>
    <script src="/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <!-- <script src="/components/angular/angular.min.js"></script> -->
    <script type="text/javascript" src="/isteven-angular-multiselect/isteven-multi-select.js"></script> 
    <script src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>
    <script src="/scripts/jquery.stickytableheaders.js"></script>
    <!-- Old Script Starts Here -->    
    <script type="text/javascript" src="/js/hub.js"></script>
    <script type="text/javascript" src="/js/client.js"></script>
    <script type="text/javascript" src="/js/es6-promise.auto.min.js"></script>
    <script src="/vendor/d3/d3.min.js"></script>
    <script src="/vendor/c3/c3.min.js"></script>
    <script src="/vendor/ladda/dist/spin.min.js"></script>
    <script src="/vendor/ladda/dist/ladda.min.js"></script>
    <script src="/vendor/ladda/dist/ladda.jquery.min.js"></script>

    <script>
      angular.module('DIPApp', ['isteven-multi-select','ngFileUpload'])
      .controller('MainCtrl', ['$scope', '$http', function ($scope, $http) {
          $scope.activeUserProfile = user
      }])

    </script>
    <script src="/scripts/homer.js"></script>
    @section('javascript')
    @show
      
  </body>
</html>
