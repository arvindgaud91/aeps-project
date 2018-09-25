<?php use Acme\Auth\Auth;
  $user = Auth::user();
 // dd($user);
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
        <link rel="shortcut icon" href="images/_blank.png">
        <title>Hamara Kendra - a chain of e Governance Services Centres</title>
       @elseif($domain_data == 'aeps-rl.hamarakendra.com')
        <link rel="shortcut icon" href="images/_blank.png">
        <title>Hamara Kendra - a chain of e Governance Services Centres</title>
      @elseif($domain_data == 'payments.digitalworldpaymentshub.com')
        <link rel="shortcut icon">
        <title>DIGITAL WORLD PAYMENTS HUB</title>
       @elseif($domain_data == 'aeps.acospay.com')
        <link rel="shortcut icon">
         <title>AMIABLE COMTRADE AND ONLINE SHOP</title>
       @elseif($domain_data == 'aeps.himveda.co.in')
        <link rel="shortcut icon">
        <title>Himveda E-Solution Pvt.Ltd.</title>
       @elseif($domain_data == 'wallet.reijiro.co.in')
        <link rel="shortcut icon">
        <title>Reijiro</title>
      @elseif($domain_data == 'rb.myam-tech.com')
        <link rel="shortcut icon" href="images/_blank.png">
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
    <!-- <link rel="stylesheet" type="text/css" href="assets/lib/jquery.vectormap/jquery-jvectormap-1.2.2.css"/> -->
    <!-- <link rel="stylesheet" type="text/css" href="assets/lib/jqvmap/jqvmap.min.css"/> -->
    <!-- <link rel="stylesheet" type="text/css" href="assets/lib/datetimepicker/css/bootstrap-datetimepicker.min.css"/> -->
    <!--<link rel="stylesheet" href="/css/style.css" type="text/css"/>-->
      <link rel="stylesheet" href="/vendor/metisMenu/dist/metisMenu.css" />
<!--      <link rel="stylesheet" href="/vendor/animate.css/animate.css" />-->
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
            </style>

    <!--Old code hidden by PR <style media="screen">
      .head-weight {
        margin-top: 30px;
      }
    </style>
    <style>
      [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
        display: none !important;
      }
      .error-p {
        color: red;
      }
    </style>-->
  </head>
  <body ng-controller="MainCtrl" ng-cloak class="fixed-navbar sidebar-scroll ng-cloak">
<!--    <div class="be-wrapper be-fixed-sidebar" >--><!-- Old Markup Hidden by PR
      <nav class="navbar navbar-default navbar-fixed-top be-top-header">
        <div class="container-fluid">
       
          <div class="navbar-header">
            <img src="/images/{{$user->vendor->logo}}" style="margin-left: 20px; margin-top: 10px;">
          </div>
       
          <div class="navbar-header" style="margin-left: 38%">
            <img src="/images/{{$user->vendor->third_party_logo}}" style="margin-left: 20px; margin-top: 10px;">
          </div>
        
          <div class="be-right-navbar">
            <ul class="nav navbar-nav navbar-right be-user-nav">
              @if( $user->vendor->parent_id != 1024)
              <li>
               <input type="button" class="btn btn-primary btn-small" value="DMT" style="margin:15px 10px 0 0;">
              </li>
              @endif
              <!-- <li class="nav navbar-nav" >
              @if( $user->vendor->type != 4 )
                <a href="#">
                  <i class="icon mdi mdi-balance-wallet">&nbsp;<b>SA:</b></i><span>&nbsp;
                  <i class="fa fa-inr" aria-hidden="true"></i>&nbsp;</span>
                  {{$user->vendor->balance}}
                </a>
              @endif
              </li>

               <li class="nav navbar-nav" >
                |
              </li> --><!--
          @if($user->vendor->master_wallet_id == 1 && $user->vendor->type == 1)
            <li class="nav navbar-nav" ></li>      
          @else
            <li class="nav navbar-nav" >
              @if( $user->vendor->type != 4 && $user->vendor->type != 5 && $user->vendor->type != 6 && $user->vendor->type != 7 && $user->vendor->type != 10 && $user->vendor->type != 11)
                <a href="#">
                  <i class="icon mdi mdi-balance-wallet"></i><span>&nbsp;
                  <i class="fa fa-inr" aria-hidden="true"></i>&nbsp;</span>
                  {{number_format(@$user->vendor->balance,2)}}
                </a>
              @endif
            </li>
          @endif

              <li class="dropdown">
                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle"><img src="/images/avatar3.png" alt="admin"><span class="user-name">Username</span></a>
                <ul role="menu" class="dropdown-menu">
                  <li>
                    <div class="user-info">
                      <div class="user-name">Name: {{$user->name}}</div>
                      <!--- <div class="user-position online">Available</div> --><!--
                    </div>
                  </li>
                  <!-- <li><a href="changePassword.php"><span class="mdi mdi-lock-open"></span>&nbsp; Change Password</a></li> --><!--
                  <li><a href="/logout"><span class="icon mdi mdi-power"></span> Logout</a></li>
                </ul>
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right be-icons-nav"></ul>
          </div>
        </div>
      </nav>-->
        <div id="header">
            <div class="color-line">
            </div>
            <div id="logo" class="light-version">
                <span>
                   <img style="height: 50px;" src="/images/{{$user->vendor->logo}}" alt="Logo" />
                </span>
            </div>
            <nav role="navigation">
                @include('partials.top-bar')
            </nav>
        </div>
        <aside id="menu">
            @include('partials.main-nav')
        </aside>
        <div id="wrapper">
            <div class="content">
                @section('content')
                @show
            </div>
            <!-- Footer-->
            <footer class="footer">
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
                &copy; DIGITAL WORLD PAYMENTS HUB.
            </div>
        @elseif($domain_data == 'aeps.acospay.com')
            <div class="col-md-12 text-center">
                &copy; AMIABLE COMTRADE AND ONLINE SHOP PVT.LTD
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
              MY AM TECH
            </div>
        @else
            <div class="col-md-12 text-center">
                Digital India Payments Limited. Copyright <?php echo date("Y"); ?>.
            </div>
        @endif
            </footer>
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
    <script type="text/javascript">
      $(document).ready(function(){
            
            //initialize the javascript
            App.init();
            // App.dashboard();
            App.masks();
            
            $('#example, #example1, #example2, #example3').DataTable( {

                fixedHeader: true,
                buttons: [
                    {extend: 'copy',className: 'btn-sm'},
                    {extend: 'csv',title: 'ExampleFile', className: 'btn-sm'},
                    {extend: 'pdf', title: 'ExampleFile', className: 'btn-sm'},
                    {extend: 'print',className: 'btn-sm'}
                ],
                "ajax": 'api/datatables.json',
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            } );

            $('#transaction-report').DataTable( {

                fixedHeader: true,
                buttons: [
                    {extend: 'copy',className: 'btn-sm'},
                    {extend: 'csv',title: 'ExampleFile', className: 'btn-sm'},
                    {extend: 'pdf', title: 'ExampleFile', className: 'btn-sm'},
                    {extend: 'print',className: 'btn-sm'}
                ],
                "ajax": 'api/datatables.json',
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                "scrollY":"420px",
                "scrollX":true,
                "scrollCollapse": true
            } );

            $(".datatable").dataTable({
              "processing": true,
              "serverSide": true,
              "dom": 'Bfrtip',
              "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000], 
                    ['10 rows', '25 rows', '50 rows', '100 rows', '500 rows', '1000 rows']
              ],
              "buttons": [
                   'copy', 'csv', 'excel', 'print', 'pageLength'
              ],
              "ajax": "/wallet-reports-admin",
              "order": [[1,'desc']],
              "columnDefs": [ { //this prevents errors if the data is null
                "targets": "_all",
                "defaultContent": ""
              } ],
            "columns": [
                //title will auto-generate th columns
                { "data" : "id", "title" : "Id", "orderable": true, "searchable": false },
                { "data" : "created_at", "title" : "Date", "orderable": true, "searchable": true },
                { "data" : "amount", "title" : "Amount", "orderable": true, "searchable": true,render: function ( data, type, row ) { return 'Rs.'+ data;} },
                { "data" : "bank", "title" : "Bank", "orderable": true, "searchable": true },
                { "data" : "branch", "title" : "Branch", "orderable": true, "searchable": false },
                { "data" : "transfer_mode", "title" : "Transfer Mode", "orderable": true, "searchable": true },
                { "data" : "reference_number", "title" : "Reference Number", "orderable": true, "searchable": true },
                { "data" : "status", "title" : "Status", "orderable": true, "searchable": true },
            ]


          });

            $( "input, textarea" ).keypress(function( event ) {
                 event.stopPropagation();
            });
      });
    //     $(document).keypress(function(e) {

    //     //Key 1
    //     if(e.which == 49) {
    //         window.location.replace("/transaction");

    //     }
    //     //Key 2
    //     if(e.which == 50) {
    //         window.location.replace("/from-distributor");
    //     }
    //     //Key 3
    //     if(e.which == 51) {
    //         window.location.replace("/balance-request");
    //     }
    //     //Key 4
    //     if(e.which == 52) {
    //         window.location.replace("/transaction-report");
    //     }
    //     //Key 5
    //     if(e.which == 53) {
    //         window.location.replace("/wallet-report");
    //     }
    //     //Key 6
    //     if(e.which == 54) {
    //         window.location.replace("/commision-report");
    //     }
    //     //Key 7
    //     if(e.which == 55) {
    //         window.location.replace("/profile");
    //     }
    //     //Key 8
    //     if(e.which == 56) {
    //         window.location.replace("/change-password");
    //     }
    //     //Key 9
    //     if(e.which == 57) {
    //         window.location.replace("/support");
    //     }
        
    // });
    </script>
    <script type="text/javascript">
      $(".sidebar-elements li").click(function() {
        if ($(".sidebar-elements li").removeClass("active")) {

            $(this).removeClass("active");
        }
        $(this).addClass("active");
        });
    </script>
    <script>
      var user = {{Auth::user()}}
     
      
      var oldToastr = toastr
      var toastr = {
        success: function (msg, title) {
          if (! title) title = 'Success'
          sweetAlert(title, msg, 'success')
        },
        error: function (msg, title) {
          if (! title) title = 'Error'
          sweetAlert(title, msg, 'error')
        },
        warning: function (msg, title) {
          if (! title) title = 'Info'
          sweetAlert(title, msg, 'warning')
        }
      }
    </script>
    <script>
      angular.module('DIPApp', ['isteven-multi-select','ngFileUpload'])
      .controller('MainCtrl', ['$scope', '$http', function ($scope, $http) {
          $scope.activeUserProfile = user
          $scope.alertDisable = alertDisable
          function alertDisable() {
            sweetAlert('Warning','Currently this facility is unavilable with your account. For activation contact with us.', 'warning')
          } 
      }])
      .directive('isnumber', [function () {
          return {
            require: 'ngModel',
            link: function (scope, elem, attrs, ctrl) {
              ctrl.$validators.isnumber = function (modelValue, viewValue) {
                if (! modelValue) return true;
                var regex = /^\d+$/g
                return regex.test(modelValue)
              }
            }
          }
        }])
      .directive('isphoneno', [function () {
          return {
            require: 'ngModel',
            link: function (scope, elem, attrs, ctrl) {
              ctrl.$validators.isphoneno = function (modelValue, viewValue) {
                var regex = /^[0-9]+$/gi;
                if(! modelValue) return true;
                if (modelValue.length == 10 && regex.test(modelValue)) return true;
                return false;
              }
            }
          }
        }])
      .directive('isvalidpincode', [function () {
          return {
            require: 'ngModel',
            link: function (scope, elem, attrs, ctrl) {
              ctrl.$validators.isvalidpincode = function (modelValue, viewValue) {
                var regex = /^[0-9]{6}$/gi;
                if(! modelValue) return true;
                if (regex.test(modelValue)) return true;
                return false;
              }
            }
          }
        }])

      .directive('fileModel', ['$parse', function ($parse) {
          return {
          restrict: 'A',
          link: function(scope, element, attrs) {
              var model = $parse(attrs.fileModel);
              var modelSetter = model.assign;

              element.bind('change', function(){
                  scope.$apply(function(){
                      modelSetter(scope, element[0].files[0]);
                  });
              });
          }
         };
      }])

      .service('fileUpload', ['$http', function ($http) {
          this.uploadFileToUrl = function(file, uploadUrl){
              var fd = new FormData();
              fd.append('file', file);
              $http.post(uploadUrl, fd, {
                  transformRequest: angular.identity,
                  headers: {'Content-Type': undefined}
              })
              .success(function(){
              })
              .error(function(){
              });
          }
      }])

      function alertDisable() {
            sweetAlert('Warning','Currently this facility is unavilable with your account. For activation contact with us.', 'warning')
      } 
    </script>
    <script src="/scripts/homer.js"></script>
    @section('javascript')
    @show
      
  </body>
</html>
