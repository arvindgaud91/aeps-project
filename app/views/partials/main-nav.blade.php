<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;
$user = Auth::user();
?>

<!-- Navigation -->

    <div id="navigation">
        <div class="profile-picture">
            <a ng-href="/users/@{{ activeUserProfile.id }}/profile">
                <?php
                    if($user->vendor->user_profile_url == NULL){
                    ?>
                    <img height="76px" src="/images/profile-pic.jpg" class="img-circle m-b" alt="logo">
                    <?php
                    }else{
                    ?>
                    <img height="76px" src="/upload/profile/{{$user->vendor->user_profile_url}}" class="img-circle m-b" alt="logo">
                    <?php
                    }
                ?>
            </a>

            <div class="stats-label text-color">
                <span class="font-extra-bold font-uppercase">{{$user->name}}</span>

                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                        <small class="text-muted">
                            @if( Auth::user()->isAgent())
                                Agent
                            @endif
                            @if( Auth::user()->isDistributor())
                                Distributor
                            @endif
                            @if( Auth::user()->isSuperDistributor())
                                Super Distributor
                            @endif
                            @if( Auth::user()->isSalesExecutive())
                                Sales Executive
                            @endif
                            @if( Auth::user()->isAreaSalesOfficer())
                                Area Sales Officer
                            @endif
                            @if( Auth::user()->isAreaSalesManager())
                                Area Sales Manager
                            @endif
                            @if( Auth::user()->isClusterHead())
                                Cluster Head
                            @endif
                            @if( Auth::user()->isStateHead())
                                State Head
                            @endif
                            @if( Auth::user()->isRegionalHead())
                                Regional Head
                            @endif
                            <b class="caret"></b>
                        </small>
                    </a>
                    <ul class="dropdown-menu animated flipInX m-t-xs">
                        <!-- <li><a href="contacts.html">Contacts</a></li> -->
                        <li><a ng-href="/users/@{{ activeUserProfile.id }}/profile">Profile</a></li>
                        <!-- <li><a href="analytics.html">Analytics</a></li> -->
                        <li class="divider"></li>
                        <li><a href="/logout">Logout</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-extra-bold m-b-xs">
                        <a href="#">
                            <!-- <i class="icon mdi mdi-balance-wallet"></i><span>&nbsp;-->
                            <span><i class="fa fa-inr" aria-hidden="true"></i> {{number_format($user->vendor->balance, 2)}}</span>
                        </a>
                    </h4>

                </div>
            </div>
        </div>
        <ul class="nav" id="side-menu">
            <li>
                <a href="/"> <span class="nav-label">Dashboard</span></a>
            </li>
            @if( Auth::user()->isAgent() )
            <li>
                <a href="#"><span class="nav-label">AEPS</span><span class="fa fa-rupee pull-right" ></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="/transactions/transact?type=balance-enquiry">Balance Enquiry</a></li>
                    <li><a href="/transactions/transact?type=withdraw">Withdraw</a></li>
                    @if(!(Auth::user()->id == 1111 || Auth::user()->id == 1076 || Auth::user()->id == 1701 || Auth::user()->id == 703 || Auth::user()->id == 1819 || Auth::user()->id == 2090 || Auth::user()->id == 1418 || Auth::user()->id == 922 || Auth::user()->id == 1839 || Auth::user()->id == 1755  || Auth::user()->id == 582  || Auth::user()->id == 1579  || Auth::user()->id == 1314 || Auth::user()->id == 604 || Auth::user()->id == 618 || Auth::user()->id == 579 || Auth::user()->id == 819  || Auth::user()->id == 366 || Auth::user()->id == 760  || Auth::user()->id == 725  || Auth::user()->id == 1081 || Auth::user()->id == 1151 || Auth::user()->id == 1083 || Auth::user()->id == 713 || Auth::user()->id == 1378 || Auth::user()->id == 1036  || Auth::user()->id == 1318 || Auth::user()->id == 1930 || Auth::user()->id == 1655 || Auth::user()->id == 1919 || Auth::user()->id == 847 || Auth::user()->id == 1399 || Auth::user()->id == 1640 || Auth::user()->id == 885 || Auth::user()->id == 710 || Auth::user()->id == 865 || Auth::user()->id == 920 || Auth::user()->id == 534 || Auth::user()->id == 1862 || Auth::user()->id == 413 || Auth::user()->id == 647 || Auth::user()->id == 1464 || Auth::user()->id == 1315 || Auth::user()->id == 1338 || Auth::user()->id == 197 || Auth::user()->id == 1923 || Auth::user()->id == 794 || Auth::user()->id == 2349 || Auth::user()->id == 1499 || Auth::user()->id ==1506 || Auth::user()->id == 3863 || Auth::user()->id == 2108 || Auth::user()->id == 3751 || Auth::user()->id == 256 || Auth::user()->id == 3760|| Auth::user()->id == 1848 || Auth::user()->id == 2547  || Auth::user()->id == 3285 || Auth::user()->id == 3026  || Auth::user()->id ==5095 ||
                    Auth::user()->id ==5229  || Auth::user()->id ==5428 || Auth::user()->id ==5478 || Auth::user()->id ==5479 || Auth::user()->id ==5481  || Auth::user()->id ==5585  || Auth::user()->id ==4288 || Auth::user()->id ==5897  || Auth::user()->id ==5956 || Auth::user()->id ==6279 || Auth::user()->id ==6636 ||  Auth::user()->id ==7674 || Auth::user()->id ==8039 || Auth::user()->id ==8040 || Auth::user()->id ==8043 || Auth::user()->id ==8046 || Auth::user()->id ==8162  || Auth::user()->id ==9450 || Auth::user()->id ==2255 || Auth::user()->id ==10567   || Auth::user()->id==4424  || Auth::user()->id==4612   || Auth::user()->id==5384  || Auth::user()->id==5385  || Auth::user()->id==5858|| Auth::user()->id==5905  || Auth::user()->id==6801  || Auth::user()->id==6815  || Auth::user()->id==7735  || Auth::user()->id==8358  ||  Auth::user()->id==5417 || Auth::user()->id==3349 || Auth::user()->id==5984 || Auth::user()->id==5985 || Auth::user()->id==5986 || Auth::user()->id==5987 || 
Auth::user()->id==5988 || Auth::user()->id==5989 || Auth::user()->id==5990 || Auth::user()->id==5991 || Auth::user()->id==5992 || 
Auth::user()->id==5993 || Auth::user()->id==5994 || Auth::user()->id==6009 || Auth::user()->id==6010 || Auth::user()->id==6011 ||
 Auth::user()->id==6012 || Auth::user()->id==6013 || Auth::user()->id==6014 || Auth::user()->id==6015 || Auth::user()->id==6016 || 
 Auth::user()->id==6017 || Auth::user()->id==6018 || Auth::user()->id==6024 || Auth::user()->id==6027 || Auth::user()->id==6028 || 
 Auth::user()->id==6029 || Auth::user()->id==6030 || Auth::user()->id==6039 || Auth::user()->id==6040 || Auth::user()->id==6041 ||
 Auth::user()->id==6042 || Auth::user()->id==6067 || Auth::user()->id==6068 || Auth::user()->id==6069 || Auth::user()->id==6070 || 
 Auth::user()->id==6071 || Auth::user()->id==6072 || Auth::user()->id==6073 || Auth::user()->id==6074 || Auth::user()->id==6075 ||
 Auth::user()->id==6076 || Auth::user()->id==6077 || Auth::user()->id==6078 || Auth::user()->id==6079 || Auth::user()->id==6080 ||
 Auth::user()->id==6081 || Auth::user()->id==6082 || Auth::user()->id==6083 || Auth::user()->id==6084 || Auth::user()->id==6085 ||
 Auth::user()->id==6086 || Auth::user()->id==6087 || Auth::user()->id==6088 || Auth::user()->id==6089 || Auth::user()->id==6090 || 
 Auth::user()->id==6091 || Auth::user()->id==6092 || Auth::user()->id==6093 || Auth::user()->id==6094 || Auth::user()->id==6095 || 
 Auth::user()->id==6096 || Auth::user()->id==6097 || Auth::user()->id==6098 || Auth::user()->id==6099 || Auth::user()->id==6100 || 
 Auth::user()->id==6101 || Auth::user()->id==6102 || Auth::user()->id==6103 || Auth::user()->id==6104 || Auth::user()->id==6105 || 
 Auth::user()->id==6106 || Auth::user()->id==6107 || Auth::user()->id==6108 || Auth::user()->id==6109 || Auth::user()->id==6110 || 
 Auth::user()->id==6111 || Auth::user()->id==6112 || Auth::user()->id==6113 || Auth::user()->id==6114 || Auth::user()->id==6115 ||
 Auth::user()->id==6116 || Auth::user()->id==6117 || Auth::user()->id==6118 || Auth::user()->id==6119 || Auth::user()->id==6120 || 
Auth::user()->id==6121 || Auth::user()->id==6122 || Auth::user()->id==6123 || Auth::user()->id== 6124 || Auth::user()->id== 6125  || Auth::user()->id== 1822  || Auth::user()->id==4354 ))
                    <li><a href="/transactions/transact?type=deposit">Deposit</a></li>
                    @endif
                </ul>
            </li>
            @endif
            
            @if(! Auth::user()->isRegionalHead() && ! Auth::user()->isStateHead() && ! Auth::user()->isClusterHead() && ! Auth::user()->isAreaSalesManager() && ! Auth::user()->isAreaSalesManager() && ! Auth::user()->isAreaSalesOfficer() && ! Auth::user()->isSalesExecutive())
                @if($user->vendor->master_wallet_id != 1)
                    <li class="">
                        <a href="#"><span class="nav-label">Request</span><span class="fa fa-download pull-right" ></span> </a>
                        <ul class="nav nav-second-level">
                            @if( Auth::user()->isAgent() )
                            <li><a href="/wallets/balance-request/from-distributor">Distributor </a></li>
                            @elseif( Auth::user()->isDistributor() )
                            <li><a href="/wallets/balance-request/from-super-distributor">Super Distributor </a></li>
                            @endif
                            <?php
                              $domain_data = preg_replace('#^https?://#', '', Request::root());
                            ?>
                            @if( $user->vendor->parent_id != 1024 && !($domain_data == 'am-tech.digitalindiapayments.com') && $user->vendor->parent_id != 8130 && $user->id != 8130 && $user->vendor->parent_id != 16337 && $user->id != 16337 && $user->vendor->parent_id != 16335 && $user->id != 16335 && $user->id != 7975 && $user->vendor->parent_id != 7975 && $user->id != 12215 && $user->vendor->parent_id != 12215 && $user->id != 17556 && $user->vendor->parent_id != 17556)
                                <li><a href="/wallets/balance-request">DIPL</a></li>
                                @if( Auth::user()->isDistributor() || Auth::user()->isSuperDistributor() )
                                    @if($user->vendor->master_wallet_id != 1)
                                    <li><a ng-href="/wallets/balance-request/incoming/vendor/@{{ activeUserProfile.id }}">INCOMING REQUESTS  </a></li>
                                    @endif
                                @endif
                            @endif
                        </ul>
                    </li>
                @endif
            @endif
          
            @if( Auth::user()->isSuperDistributor() )
                <li>
                    <a href="/distributors"> <span class="nav-label">MY DISTRIBUTORS</span> </a>
                </li>
            @endif

            @if( Auth::user()->isDistributor() )
                <li>
                    <a  href="/agents"> <span class="nav-label">MY AGENTS</span></a>
                </li>
            @endif
            @if( Auth::user()->isSalesExecutive())
            <!-- <li><a href="/bc-agent-registration"><i class="icon mdi mdi-receipt"></i><span>BC Agent Registration</span></a></li> -->
            @endif

    

        
            
            <li class="">
                <a href="#"><span class="nav-label">Report</span><span class="fa fa-list-alt pull-right" ></span> </a>
                <ul class="nav nav-second-level">
                    @if( Auth::user()->isAgent())
                        <li><a href="/transaction-reports">Transaction </a></li>
                         @if($user->vendor->master_wallet_id != 1)
                            <li><a href="/wallet-reports">Wallet  </a></li>
                        @endif
                    @endif
                    
                    @if( Auth::user()->isDistributor())
                        <li><a href="/transaction-report">Transaction </a></li>
                        <li><a href="/wallet-report">Wallet  </a></li>
                        <li><a href="/commission-reports">Commission</a></li>
                    @endif
                    
                    @if( Auth::user()->isSuperDistributor())
                        <li><a href="/transaction-report">Transaction </a></li>
                        <li><a href="/wallet-report">Wallet  </a></li>
                    @endif
                    
                    @if( Auth::user()->isSalesExecutive())
                        <li><a href="/distributor-sales-executive-report">SALES EXECUTIVE </a></li>
                        <!-- <li><a href="/bc-agent-report"><span class="mdi mdi-money-box"></span>&nbsp; BC AGENT</a></li> -->
                    @endif
                    
                    @if( Auth::user()->isAreaSalesOfficer())
                        <li><a href="/area-sales-officer-report">SALES OFFICER </a></li>
                    @endif
                    
                    @if( Auth::user()->isAreaSalesManager())
                        <li><a href="/area-sales-manager-report">AREA SALES MANAGER </a></li>
                    @endif
                    
                    @if( Auth::user()->isClusterHead())
                        <li><a href="/cluster-head-report">CLUSTER HEAD</a></li>
                    @endif
                    
                    @if( Auth::user()->isStateHead())
                        <li><a href="/state-head-report">STATE HEAD</a></li>
                    @endif
                    
                    @if( Auth::user()->isRegionalHead())
                        <li><a href="/regional-head-report">REGIONAL HEAD</a></li>
                    @endif
                </ul>
            </li>
           @if( ! Auth::user()->isRegionalHead() && !Auth::user()->isStateHead() && ! Auth::user()->isClusterHead() &&! Auth::user()->isAreaSalesManager() && ! Auth::user()->isAreaSalesOfficer() && ! Auth::user()->isSalesExecutive() && Auth::user()->isAgent() || Auth::user()->id==13 || Auth::user()->id==1459 || Auth::user()->id==1924 )
           @if($user->vendor->parent_id != 8130 && $user->vendor->parent_id != 16337 && $user->vendor->parent_id != 16335 && $user->vendor->parent_id !=12215 && $user->vendor->parent_id !=17556 )


           @if(!(Auth::user()->id == 1111 || Auth::user()->id == 1076 || Auth::user()->id == 1701 || Auth::user()->id == 703 || Auth::user()->id == 1819  || Auth::user()->id == 2090 || Auth::user()->id == 1418 || Auth::user()->id == 922 || Auth::user()->id == 1839 || Auth::user()->id == 1755  || Auth::user()->id == 582  || Auth::user()->id == 1579  || Auth::user()->id == 1314 || Auth::user()->id == 604 || Auth::user()->id == 618 || Auth::user()->id == 579 || Auth::user()->id == 819  || Auth::user()->id == 366 || Auth::user()->id == 760  || Auth::user()->id == 725  || Auth::user()->id == 1081 || Auth::user()->id == 1151 || Auth::user()->id == 1083 || Auth::user()->id == 713 || Auth::user()->id == 1378 || Auth::user()->id == 1036  || Auth::user()->id == 1318 || Auth::user()->id == 1930 || Auth::user()->id == 1655 || Auth::user()->id == 1919 || Auth::user()->id == 847 || Auth::user()->id == 1399 || Auth::user()->id == 1640 || Auth::user()->id == 885 || Auth::user()->id == 710 || Auth::user()->id == 865 || Auth::user()->id == 920 || Auth::user()->id == 534 || Auth::user()->id == 1862 || Auth::user()->id == 413 || Auth::user()->id == 647 || Auth::user()->id == 1464 || Auth::user()->id == 1315 || Auth::user()->id == 1338 || Auth::user()->id == 197 || Auth::user()->id == 1923 || Auth::user()->id == 794 || Auth::user()->id == 2349 || Auth::user()->id == 1499 || Auth::user()->id ==1506 || Auth::user()->id == 3863 || Auth::user()->id == 2108 || Auth::user()->id == 3751 || Auth::user()->id == 256 || Auth::user()->id == 3760|| Auth::user()->id == 1848 || Auth::user()->id == 2547  || Auth::user()->id == 3285 || Auth::user()->id == 3026  || Auth::user()->id ==5095 || 
                    Auth::user()->id ==5229 || Auth::user()->id ==5428 || Auth::user()->id ==5478 || Auth::user()->id ==5479 || Auth::user()->id ==5481 || Auth::user()->id ==5585 || Auth::user()->id ==4288 || Auth::user()->id ==5897 || Auth::user()->id ==5956 || Auth::user()->id ==6279 || Auth::user()->id ==6636 || Auth::user()->id ==7674 || Auth::user()->id ==8039 || Auth::user()->id ==8040 || Auth::user()->id ==8043 || Auth::user()->id ==8046 || Auth::user()->id ==8162 || Auth::user()->id ==9450 || Auth::user()->id ==2255 || Auth::user()->id ==10567|| Auth::user()->id==4424  || Auth::user()->id==4612  ||  Auth::user()->id==5384  || Auth::user()->id==5385  || Auth::user()->id==5858  || Auth::user()->id==5905  || Auth::user()->id==6815  || Auth::user()->id==7735  || Auth::user()->id==8358  || Auth::user()->id==5417  || Auth::user()->id==1822  || Auth::user()->id==4354 ))
            <li class="">
                <a href="#"><span class="nav-label">SETTLEMENT</span><span class="fa fa-handshake-o pull-right" ></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="/settlement">Settlement Details </a></li>
                    <li><a href="/settlement-request">Settlement Request  </a></li>
                    <li><a href="/settlement-report">Settlement Report</a></li>
                </ul>
            </li>
             @endif
            @endif
            @endif
            
            @if((Auth::user()->isDistributor() || Auth::user()->isAgent()) && Session::get('dmt_user')==1 && ($user->vendor->parent_id != 1024 ))
                   @if($user->vendor->parent_id != 8130 && $user->vendor->parent_id != 16337 && $user->vendor->parent_id != 16335 && $user->vendor->parent_id !=12215 && $user->vendor->parent_id !=17556 && $user->id != 17556) 
                      
            <li class="">
                <a href="#"><span class="nav-label">TRANSFER TO DMT</span><span class="fa fa-exchange pull-right" ></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="/dmt-transfer-request/{{Auth::user()->id}}">AEPS to DMT Request</a></li>
                    <li><a href="/dmt-transfer-report">Report  </a></li>
                </ul>
            </li>
            
             @endif
            @endif
            
            @if(! Auth::user()->isRegionalHead() && !Auth::user()->isStateHead() && ! Auth::user()->isClusterHead() &&! Auth::user()->isAreaSalesManager() && ! Auth::user()->isAreaSalesOfficer() && ! Auth::user()->isSalesExecutive())
            <!-- <li class="parent"><a href="#"><i class="icon mdi mdi-headset"></i><span>SUPPORT</span></a>
              <ul class="sub-menu">
                  <li><a ng-href="/ticket"><span class="icon mdi mdi-file">  &nbsp;REQUEST SUPPORT</span></a></li>
                  
              </ul>
            </li> -->

            @if($user->vendor->parent_id != 8130 && $user->id != 8130 && $user->vendor->parent_id != 16337 && $user->id != 16337 && $user->vendor->parent_id != 16335 && $user->id != 16335 && $user->vendor->parent_id !=12215 && $user->id != 12215 && $user->vendor->parent_id !=17556 && $user->id != 17556 && $domain_data != 'aeps-rl.hamarakendra.com' && $domain_data != 'aeps.hamarakendra.com')
            <li class=""><a href="/ticket"><span>SUPPORT</span>
              <span class="label label-warning pull-right">NEW</span></a>
            </li>
            @endif
            @if($user->id == 311 || $user->id== 5279 || $user->id == 986 || $user->id==963)
            <li class=""><a href="/rd-link" target="_blank"><span>RD Browser Link</span></a>
            </li>

            <!-- <li class="">
                <a href="#"><span class="nav-label">RD Browser Link</span><span class="fa fa-exchange pull-right" ></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="https://127.0.0.1:8005/rd/capture" target="_blank">Mozilla Browser</a></li>
                    <li><a href="chrome://flags/#allow-insecure-localhost" target="_blank">Chrome Browser</a></li>
                </ul>
            </li> -->
            @endif
            @endif
            
            <li class="">
                <a href="#"><span class="nav-label">Settings</span><span class="fa fa-gear pull-right" ></span> </a>
                <ul class="nav nav-second-level">
                    @if(! Auth::user()->isRegionalHead() && !Auth::user()->isStateHead() && ! Auth::user()->isClusterHead() &&! Auth::user()->isAreaSalesManager() && ! Auth::user()->isAreaSalesOfficer() && ! Auth::user()->isSalesExecutive())
                        <li><a ng-href="/users/@{{ activeUserProfile.id }}/profile">Profile</a></li>
                    @endif
                    <li><a href="/users/{{Auth::user()->id}}/reset-session">RESET SESSION</a></li>
                    <li><a href="/users/{{Auth::user()->id}}/actions/change-password">Change Password</a></li>
                    @if(! Auth::user()->isRegionalHead() && !Auth::user()->isStateHead() && ! Auth::user()->isClusterHead() &&! Auth::user()->isAreaSalesManager() && ! Auth::user()->isAreaSalesOfficer() && ! Auth::user()->isSalesExecutive())
                        <li><a href="/devices/select">Biometric Device</a></li>
                        @if($user->vendor->parent_id != 8130 && $user->id != 8130 && $user->vendor->parent_id != 16337 && $user->id != 16337 && $user->vendor->parent_id != 16335 && $user->id != 16335 && $user->vendor->type !=2 && $user->vendor->parent_id != 12215 && $user->id != 12215 && $user->vendor->parent_id !=17556 && $user->id != 17556)
                        <li><a ng-href="/users/@{{ activeUserProfile.id }}/certificate">Certificate</a></li>
                        @endif
                        @if($user->vendor->parent_id != 8130 && $user->id != 8130 && $user->vendor->parent_id != 16337 && $user->id != 16337 && $user->vendor->parent_id != 16335 && $user->id != 16335 && $user->vendor->parent_id != 12215 && $user->id != 12215 && $user->vendor->parent_id !=17556 && $user->id != 17556)
                        <li><a href="/download-driver">Driver</a></li>
                        
                        <li><a ng-href="/images/customer-guidelines.jpg">CUSTOMER GUIDELINES</a></li>
                        @endif
                    @endif
                </ul>
            </li>
            @if($user->vendor->fingerprint_device_id == 1 && $user->vendor->type == 1)
            <li class="">
                <a href="#"><span class="nav-label">DEVICE INFO</span><span class="fa fa-gear pull-right" ></span> </a>
            </li>
            @endif
        </ul>
        </div>
      </div>
    </div>


  

