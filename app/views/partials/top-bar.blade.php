<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;
$user = Auth::user();


$aeps = "#";
$dmt = "#";
$cpt = "#";
$irctc="#";
$indonepal="#";
$playwin="#";
Session::put('dmt_user',0);
$permissions = ServicePermission::where('user_id', Auth::user()->id)->lists('permission');
if(null != $permissions){
  foreach($permissions as $per){
    if($per =='aeps')
      $aeps = getenv('AUTH_URL');
    if($per=='dmt'){
      $dmt = getenv('DMT_URL');
      Session::put('dmt_user',1);
    }
    if($per=='cpt')
      $cpt = getenv('CP_URL');
    if($per =='irctc')
      $irctc = getenv('IRCTC_URL');
    if($per=='indonepal')
      $indonepal = getenv('INDONEPAL_URL');
    if($per=='playwin')
      $playwin = getenv('PLAYWIN_URL');
  }
}

?>    

<div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
    <div class="small-logo">
        <span class="text-primary">DIPL-AEPS</span>
    </div>
    <form role="search" class="navbar-form-custom" method="post" action="#">
        <div class="form-group">
            <input type="text" placeholder="Search something special" class="form-control" name="search">
        </div>
    </form>
    <div class="mobile-menu">
        <button type="button" class="navbar-toggle mobile-menu-toggle" data-toggle="collapse" data-target="#mobile-collapse">
            <i class="fa fa-chevron-down"></i>
        </button>
        <div class="collapse mobile-navbar" id="mobile-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a class="" href="login.html">Login</a>
                </li>
                <li>
                    <a href="/logout">Logout</a>
                </li>
                <li>
                    <a class="" href="profile.html">Profile</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="navbar-right">
        <ul class="nav navbar-nav no-borders">
            <!--<li class="dropdown">
                <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                    <i class="pe-7s-speaker"></i>
                </a>
                <ul class="dropdown-menu hdropdown notification animated flipInX">
                    <li>
                        <a>
                            <span class="label label-success">NEW</span> It is a long established.
                        </a>
                    </li>
                    <li>
                        <a>
                            <span class="label label-warning">WAR</span> There are many variations.
                        </a>
                    </li>
                    <li>
                        <a>
                            <span class="label label-danger">ERR</span> Contrary to popular belief.
                        </a>
                    </li>
                    <li class="summary"><a href="#">See all notifications</a></li>
                </ul>
            </li>-->
         
            <li class="dropdown">
                <a class="dropdown-toggle" title="Services" href="#" data-toggle="dropdown">
                    <i class="pe-7s-keypad"></i>
                </a>

                <div class="dropdown-menu hdropdown bigmenu animated flipInX">
                    <table>
                        <tbody>
                        <tr>     
                            <td>

                                 <a href="/services"
                                       
                                        style="cursor: pointer;">
                                    <i class="fa fa-inr text-info" style="font-weight: normal;font-size: 40px;line-height: 46px;"></i>
                                    <h5>SERVICE</h5>
                                </a>
                            </td>
                        </tr>
                     
                        </tbody>
                    </table>
                </div>
            </li>
          
            <!-- <li class="dropdown">
                <a class="dropdown-toggle label-menu-corner" href="#" data-toggle="dropdown">
                    <i class="pe-7s-mail"></i>
                    <span class="label label-success">4</span>
                </a>
                <ul class="dropdown-menu hdropdown animated flipInX">
                    <div class="title">
                        You have 4 new messages
                    </div>
                    <li>
                        <a>
                            It is a long established.
                        </a>
                    </li>
                    <li>
                        <a>
                            There are many variations.
                        </a>
                    </li>
                    <li>
                        <a>
                            Lorem Ipsum is simply dummy.
                        </a>
                    </li>
                    <li>
                        <a>
                            Contrary to popular belief.
                        </a>
                    </li>
                    <li class="summary"><a href="#">See All Messages</a></li>
                </ul>
            </li> -->
            <!-- <li>
                <a href="#" id="sidebar" class="right-sidebar-toggle">
                    <i class="pe-7s-upload pe-7s-news-paper"></i>
                </a>
            </li> -->
            <li class="dropdown">
                <a title="Logout" href="/logout">
                    <!-- <i class="pe-7s-upload pe-rotate-90"></i> -->
                    <i class="pe-7s-power" style="color: red;"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
