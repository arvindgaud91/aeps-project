<?php use Acme\Auth\Auth; 
	$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="en" ng-app="DIPApp">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="">
		<meta name="author" content="">
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
    		<title>Digital India Payments</title>
    	@endif
		<link rel="stylesheet" href="/sweetalert/sweetalert.css">
		<link rel="stylesheet" type="text/css" href="/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
		<link rel="stylesheet" type="text/css" href="/material-design-icons/css/material-design-iconic-font.min.css"/>
		<link rel="stylesheet" href="/css/style-new.css" type="text/css"/>
		<link rel="stylesheet" href="/css/esscale.css" type="text/css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
        
        <!-- External Files -->
        <link rel="stylesheet" href="/vendor/bootstrap/dist/css/bootstrap.css" />
        <link rel="stylesheet" href="/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" type='text/css'>
    <link rel="stylesheet" href="/fonts/pe-icon-7-stroke/css/helper.css" type='text/css'>
<!--    <link rel="stylesheet" href="/styles/style.css">-->
    <link rel="stylesheet" href="/styles/esscale.css">
    <link rel="stylesheet" href="/vendor/animate.css/animate.css" />
    <link rel="stylesheet" href="/isteven-angular-multiselect/isteven-multi-select.css">
    <link rel="stylesheet" href="/vendor/c3/c3.min.css" />
        
		<style>
            body{background: #f1f3f6;}
            a:hover { text-decoration: none; }
			.cur { cursor: pointer; }
            #wrapper { min-height: 1px; }
            #header { background: #f7f9fa; }
            .content { padding: 25px 40px 22px 40px; }
            .t85 { font-size: 85px; }
            #logo2 { width: 210px; text-align: left; }
            #logo2 img { width: auto; height: 54px; }
            footer { text-align: center; background: #f1f3f6; padding: 7px; border-top: 1px solid #ccc;}
            footer h6 { margin-bottom: 0 !important; }
		</style>
	</head>
	<body class="be-splash-screen">
        <div id="header">
            <div class="color-line">
            </div>
            <!-- <div id="logo" class="light-version">
                <span>
                   <img src="/images/cinqueterre.png" alt="DIPL Logo" />
                </span>
            </div> -->
            <div id="logo" class="light-version">
                <span>
                   <img style="height: 50px;" src="/images/{{$user->vendor->logo}}" alt="Logo" />
                </span>
            </div>
            <div id="logo2" class="pull-right"><img src="/images/rbl.png"></div>
        </div>
        
        <div id="wrapper" style="margin:0 !important;">
            <div class="content" >
                <div class="row animate-panel">
                    @if( $user->vendor->parent_id == 1024 || $user->vendor->id == 7829 || $user->vendor->parent_id == 8130 || $user->vendor->user_id == 8130 || $user->id == 7975 || $user->vendor->parent_id==7975 || $user->id == 12215 || $user->vendor->parent_id==12215 || $user->vendor->parent_id == 17556 || $user->vendor->user_id == 17556)
                        <div class="col-md-8 col-sm-12 col-md-offset-4 animate-panel" data-child="row" data-effect="fadeInUp" ng-controller="ServiceCtrl">
                    @else
                        <div class="col-md-8 col-sm-12 col-md-offset-2 animate-panel" data-child="row" data-effect="fadeInUp" ng-controller="ServiceCtrl">
                    @endif
                        <div class="row" style="">

                            @if($portal_id=='V3') 

                            <div class="col-lg-6">
                                <a href="/loginv3/{{\Cookie::get('mobileno')}}">
                                    <div class="hpanel hbggreen">
                                        <div class="panel-body">
                                            <div class="text-center">
                                                <p class="text-big font-light"><i class="fa fa-thumbs-o-up t85"></i></p>
                                                <h3>AEPS</h3>
                                                <small>Aadhaar Enabled Payment System</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @else
                            <div class="col-sm-6">
                                <a href="#">
                                    <div class="hpanel hbggreen">
                                        <div class="panel-body">
                                            <div class="text-center">
                                                <p class="text-big font-light"><i class="fa fa-id-card-o text-succes"></i></p>
                                                <h3>AEPS</h3>
                                                <small>Aadhaar Enabled Payment System</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                              @endif
                            <div class="col-sm-6">
                                <a href="/logindmtv3/{{\Cookie::get('mobileno')}}/{{urlencode(\Cookie::get('password'))}}"
										
										style="cursor: pointer;">
                                    <div class="hpanel hbgblue">
                                        <div class="panel-body">
                                            <div class="text-center">
                                                <p class="text-big font-light">
                                                    <i style="font-size: 75px;" class="fa fa-inr t85"></i>
                                                </p>
                                                <h3>DMT</h3>
                                                <small>Domestic Money Transfer</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row" style="">
                            @if($portal_id=='V3')
                            
                              <div class="col-sm-4">
                                <a href="/loginbill/{{\Cookie::get('mobileno')}}" style="cursor: pointer;">
                                    <div class="hpanel hbgviolet">
                                        <div class="panel-body">
                                            <div class="text-center">
                                                <p class="text-big font-light">
                                                    <i class="pe pe-7s-news-paper text-succes"></i>
                                                </p>
                                                <h3>Pay Bill</h3>
                                                <!-- <small>Lorem Ipsum is simply dummy text</small> -->
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>



                            @else

                            <div class="col-sm-4">
                                <a href=""
								<?php if($cpt=="#") { ?>ng-click="alertDisable()" <?php } ?> style="cursor: pointer;">
                                    <div class="hpanel hbgviolet">
                                        <div class="panel-body">
                                            <div class="text-center">
                                                <p class="text-big font-light">
                                                    <i class="pe pe-7s-news-paper text-succes"></i>
                                                </p>
                                                <h3>Pay Bill</h3>
                                                <!-- <small>Lorem Ipsum is simply dummy text</small> -->
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endif
                            <div class="col-sm-4">
                                <a href=""
										<?php if($irctc=="#") { ?>ng-click="alertDisable()" <?php } ?>
										>
                                    <div class="hpanel hbgred">
                                        <div class="panel-body">
                                            <div class="text-center">
                                                <p class="text-big font-light">
                                                    <i style="font-size: 75px;" class="fa fa-train t85"></i>
                                                </p>
                                                <h3>IRCTC</h3>
                                               <!--  <small>Lorem Ipsum is simply dummy text</small> -->
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a href="" <?php if($indonepal=="#") { ?>ng-click="alertDisable()" <?php } ?> style="cursor: pointer;">
                                    <div class="hpanel hbgyellow">
                                        <div class="panel-body">
                                            <div class="text-center">
                                                <p class="text-big font-light">
                                                    <i class="pe pe-7s-diskette text-succes"></i>
                                                </p>
                                                <h3>BBPS</h3>
                                              <!--   <small>Lorem Ipsum is simply dummy text</small> -->
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
            <footer class="fixed-footer" style="position: fixed;width: 100%;bottom: 0;">
                <div class="text-center"><img src="/images/adhar.png" style="max-height: 50px;"></div>
                <?php $domain_data = preg_replace('#^https?://#', '', Request::root()); ?> @if($domain_data == 'am-tech.digitalindiapayments.com')
                <h6>Am-Tech</h6>

                @elseif($domain_data == 'service.aepsmoney.com')
                <h6>AMIABLE COMTRADE AND ONLINE SHOP PVT.LTD</h6>

                @elseif($domain_data == 'aeps.unnayon.in')
                <h6>UNNAYON CONSULTANCY SERVICES PVT LTD</h6>
                @elseif($domain_data == 'aeps.hamarakendra.com')
                <h6>IPS e Services Pvt. Ltd.</h6>
                @elseif($domain_data == 'aeps-rl.hamarakendra.com')
                <h6>IPS e Services Pvt. Ltd.</h6>
                @elseif($domain_data == 'payments.digitalworldpaymentshub.com')
                <h6>DIGITAL WORLD PAYMENTS HUB</h6>
                @elseif($domain_data == 'aeps.himveda.co.in')
                <h6>Himveda E-Solution Pvt.Ltd.</h6>
                @elseif($domain_data == 'aeps.acospay.com')
                <h6>AMIABLE COMTRADE AND ONLINE SHOP PVT.LTD</h6>
                @elseif($domain_data == 'rb.myam-tech.com')
                <h6>MY AM TECH</h6>
                @else
                <h6>Digital India Payments Limited. Copyright <?php echo date("Y"); ?>.</h6>
                @endif
            </footer>
        </div>
        
        
        
        
        <!-- OLD CODES -->
		<!-- Header logo sidebar -->
		<!--<nav class="navbar navbar-default navbar-fixed-top be-top-header" style="height: 100px; background: whitesmoke;">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<br>
						<div class="navbar-header">
				            <img src="/images/{{$user->vendor->logo}}" style="margin-left: 20px; margin-top: 10px;">
				          </div>
				       
				          <div class="navbar-header" style="margin-left: 33%">
				            <img src="/images/{{$user->vendor->third_party_logo}}" style="margin-left: 20px; margin-top: -2px;">
				          </div>
						<div class="col-md-4"></div>
						<div class="col-md-2">
							<img src="/images/rbl.png" height="50px;">
						</div>
					</div>
				</div>
			</div>
		</nav>-->
		<!-- End header sidebar -->
        <!--
		<div ng-controller="ServiceCtrl" class="col-md-4 col-md-offset-4 head-weight">
			<div class="be-wrapper be-login">
				<div class="be-content">
					<div class="main-content container-fluid">
						<div class="splash-container services-wrapper" style="margin: 116px auto;">
							<div class="panel panel-default panel-border-color panel-border-color-primary" >
								<div class="panel-heading">
									<h4 style="text-transform: uppercase; font-weight: bold; ">Select Service</h4>
								</div>
								<div class="clearfix"></div>
							</div>
							 @if( $user->vendor->parent_id == 1024 || $user->vendor->parent_id == 8130)
							<div class="panel panel-default col-md-10">
								<div class="panel-body" style="text-align: center;">
									
									<div class="card">
										<a href="/"><img src="/images/aeps-finger.jpg" alt="Avatar" style="width:100%"></a>
										<br>
										<a href="/" class="btn btn-primary btn-block">AEPS</a>
									</div>
									
								</div>
								<div class="clearfix"></div>
							</div>
							@else

							<div class="panel panel-default col-md-6">
								<div class="panel-body" style="text-align: center;">
									
									<div class="card">
										<a href="/"><img src="/images/aeps-finger.jpg" alt="Avatar" style="width:100%"></a>
										<br>
										<a href="/" class="btn btn-primary btn-block">AEPS</a>
									</div>
									
								</div>
								<div class="clearfix"></div>
							</div>
 
							<div class="panel panel-default col-md-6">
								<div class="panel-body" style="text-align: center;">
									<div class="card">
										<a href="<?php echo $dmt; ?>"
										<?php if($dmt=="#") { ?>ng-click="alertDisable()" <?php } ?>
										style="cursor: pointer;"><img src="/images/dmt-rupee.jpg" alt="Avatar" style="width:100%" ng-click></a>
										<br>
										<a href="<?php echo $dmt; ?>" class="btn btn-primary btn-block" <?php if($dmt=="#") { ?>disabled ng-click="alertDisable()" <?php } ?>>DMT</a>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="panel panel-default col-md-4" >
								<div class="panel-body" style="text-align: center;">
									<div class="card">
										<a href="<?php echo $cpt; ?>"
										<?php if($cpt=="#") { ?>ng-click="alertDisable()" <?php } ?>
										style="cursor: pointer;"><img src="/images/cyberplat1.jpg" alt="Avatar" style="width:100%"></a>
										<br>
										<a href="<?php echo $cpt; ?>" class="btn btn-primary btn-block"  <?php if($cpt=="#"){ ?>disabled ng-click="alertDisable()" <?php } ?>>Pay Bill</a>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>

							<div class="panel panel-default col-md-4" >
								<div class="panel-body" style="text-align: center;">
									<div class="card">
										<a href="<?php echo $irctc; ?>"
										<?php if($irctc=="#") { ?>ng-click="alertDisable()" <?php } ?>
										><img src="/images/irctc.jpg" alt="Avatar" style="width:100%"></a>
										<br>
										<a href="<?php echo $irctc; ?>" class="btn btn-primary btn-block" <?php if($irctc=="#") { ?>disabled ng-click="alertDisable()" <?php } ?>>IRCTC</a>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="panel panel-default col-md-4">
								<div class="panel-body" style="text-align: center;">
									<div class="card">
										<a href="<?php echo $indonepal; ?>"
										<?php if($indonepal=="#") { ?>ng-click="alertDisable()" <?php } ?>
										style="cursor: pointer;"><img src="/images/indo-nepal.jpg" alt="Avatar" style="width:100%" ng-click></a>
										<br>
										<a href="<?php echo $indonepal; ?>" class="btn btn-primary btn-block" <?php if($indonepal=="#") { ?>disabled ng-click="alertDisable()" <?php } ?>>IN-NEP</a>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
							@endif
							
							

							<!-- Can Add Signup Link --><!--
						</div>
                        <!--
						<div class="footer" align="center" style="margin-top: -80px;">
							<!--<img src="images/digital.png" height="50px;">--><!--
							&nbsp;&nbsp;&nbsp;&nbsp;<img src="/images/adhar.png" height="50px;"> <br>
							 <?php $domain_data = preg_replace('#^https?://#', '', Request::root()); ?>

	        						@if($domain_data == 'am-tech.digitalindiapayments.com')
	        							<h6>Am-Tech</h6>
							 
							        @elseif($domain_data == 'service.aepsmoney.com')
						                <h6>AMIABLE COMTRADE AND ONLINE SHOP PVT.LTD</h6>
							 
							        @elseif($domain_data == 'aeps.unnayon.in')
							            <h6>UNNAYON CONSULTANCY SERVICES PVT LTD</h6>
							        @elseif($domain_data == 'aeps.hamarakendra.com')
							            <h6>IPS e Services Pvt. Ltd.</h6>
							        @elseif($domain_data == 'payments.digitalworldpaymentshub.com')
							            <h6>DIGITAL WORLD PAYMENTS HUB</h6>
							        @else
										<h6>Digital India Payments Limited. Copyright 2017.</h6>
							        @endif
						</div>
                        --><!--
					</div>
				</div>
			</div>
			
		</div>
        -->
	<script src="/sweetalert/sweetalert.min.js"></script>
	<script src="/components/jquery/dist/jquery.min.js"></script>
	<script src="/components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="/components/angular/angular.min.js"></script>
	<script src="/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
    <script src="/components/toastr/toastr.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="/components/angular/angular.min.js"></script>
    <script src="/jquery.sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <script src="/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/isteven-angular-multiselect/isteven-multi-select.js"></script>
    <script type="text/javascript" src="/x2js/xml2json.js"></script>
    <script src="/vendor/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="/vendor/metisMenu/dist/metisMenu.min.js"></script>
    <script src="/scripts/homer.js"></script>
    <script type="text/javascript">
    
		
			var user = {{json_encode($user)}}
		</script>
		<script>
			angular.module('DIPApp', [])
				.controller('ServiceCtrl', ['$scope', '$http', function ($scope, $http) {
					window.s = $scope
					$scope.activeUserProfile = user	
					$scope.alertDisable = alertDisable
					function alertDisable() {
						sweetAlert('Warning','Currently this facility is unavilable with your account. For activation contact with us.', 'warning')
					} 
					
					
				}])
		</script>
		<!-- <script src="assets/js/main.js" type="text/javascript"></script> -->
	</body>
</html>