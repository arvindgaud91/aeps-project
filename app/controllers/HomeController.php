<?php

use Acme\Auth\Auth;
use Carbon\Carbon;

class HomeController extends BaseController {

  public function __construct ()
  {
    $this->beforeFilter('auth', [
      'only' => ['index']
    ]);
    $this->beforeFilter('guest', [
      'only' => ['landing']
    ]);
  }

  public function index ()
  {
       $AepsTransaction = new  AepsTransaction;
       $AepsTransaction->setConnection('mysql1');

       $AepsWalletAction = new AepsWalletAction;
       $AepsWalletAction->setConnection('mysql1');

       $Vendor=new Vendor;
       $Vendor->setConnection('mysql1');

    $vendorType = $Vendor->type(Auth::user()->vendorDetails->type);

    if (Auth::user()->vendorDetails->type == 1) {
      $data['banks'] = Bank::get();

      $data['transactions'] = AepsTransaction::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->take(5)->get();
      $data['deposit_amount_today'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) = ?', [Carbon::today()])->where('type', 1)->where('result', 1)->sum('amount');
      $data['deposit_count_today'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) = ?', [Carbon::today()])->where('type', 1)->where('result', 1)->count();
      $data['withdraw_amount_today'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) = ?', [Carbon::today()])->where('type', 2)->where('result', 1)->sum('amount');
      $data['withdraw_count_today'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) = ?', [Carbon::today()])->where('type', 2)->where('result', 1)->count();
      $data['balance_enquiry_count_today'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) = ?', [Carbon::today()])->where('type', 0)->where('result', 1)->count();
      $data['deposit_amount_weekly'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])->where('type', 1)->where('result', 1)->sum('amount');
      $data['deposit_count_weekly'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])->where('type', 1)->where('result', 1)->count();
      $data['withdraw_amount_weekly'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])->where('type', 2)->where('result', 1)->sum('amount');
      $data['withdraw_count_weekly'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])->where('type', 2)->where('result', 1)->count();
      $data['balance_enquiry_count_weekly'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])->where('type', 0)->where('result', 1)->count();
      /*$data['deposit_amount_monthly'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])->where('type', 1)->where('result', 1)->sum('amount');
      $data['deposit_count_monthly'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])->where('type', 1)->where('result', 1)->count();
      $data['withdraw_amount_monthly'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])->where('type', 2)->where('result', 1)->sum('amount');
      $data['withdraw_count_monthly'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])->where('type', 2)->where('result', 1)->count();
      $data['balance_enquiry_count_monthly'] = AepsTransaction::where('user_id', Auth::user()->id)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])->where('type', 0)->where('result', 1)->count();*/
      $data['commission_today'] = AepsWalletAction::where('user_id', Auth::user()->id)->where('commission', 1)->whereRaw('date(created_at) = ?', [Carbon::today()])->sum('amount');
      $data['commission_weekly'] = AepsWalletAction::where('user_id', Auth::user()->id)->where('commission', 1)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])->sum('amount');
      /*$data['commission_monthly'] = AepsWalletAction::where('user_id', Auth::user()->id)->where('commission', 1)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])->sum('amount');*/

      return View::make('home.index-agent')->with($data);
    }

    $data['distributor_count'] = 0;
         //dd($vendorType['child_type_id']);
         //dd(Auth::user()->id);
         if (Auth::user()->vendorDetails->type == 3){
              $distribuotr_ids = $Vendor->where('parent_id', Auth::user()->id)->where('type', $vendorType['child_type_id'])->lists('user_id');
              $data['distributor_count'] = sizeOf($distribuotr_ids);
             // dd( $data['distributor_count'] );
               $children = $Vendor->whereIn('parent_id', $distribuotr_ids)->where('type', 1)->get();
         }
         if (Auth::user()->vendorDetails->type == 2){
            
            //  $children = $Vendor->where('parent_id', Auth::user()->id)->where('type', $vendorType['child_type_id'])->get();
         }
       
        $data['childBalance'] = 0;
        $data['transactions_today'] = 0;
        $data['amount_today']=0;
        $data['distributor_commission_today']=0;
        
        $data['transactions_weekly'] = 0;
        $data['amount_weekly']=0;
        $data['distributor_commission_weekly']=0;
        
        $data['transactions_monthly'] = 0;
        $data['amount_monthly']=0;
        $data['distributor_commission_monthly']=0;
        
      //    $data['distributor_commission_today'] = $AepsWalletAction->where('user_id', Auth::user()->id)->where('commission', 1)->whereRaw('date(created_at) = ?', [Carbon::today()])->sum('amount');

      // $data['distributor_commission_weekly'] = $AepsWalletAction->where('user_id', Auth::user()->id)->where('commission', 1)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])->sum('amount');

      //  $data['distributor_commission_monthly'] = $AepsWalletAction->where('user_id', Auth::user()->id)->where('commission', 1)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])->sum('amount');


        if(!empty($children))
        {  $WalletTransaction = new WalletTransaction;
       $WalletTransaction->setConnection('mysql1');

           $data['distributor_commission_today'] = $WalletTransaction->where('user_id',  Auth::user()->id)->where('activity', 'credit-commission')->whereRaw('date(created_at) = ?', [Carbon::today()])->sum('amount');


        foreach ($children as $child) {
            
            
           $data['childBalance'] += $child['balance'];

           //   $data['transactions_today'] += $AepsTransaction->where('user_id', $child['user_id'])->whereRaw('date(created_at) = ?', [Carbon::today()])->where('result', 1)->count();
           //   $data['amount_today'] += $AepsTransaction->where('user_id', $child['user_id'])->whereRaw('date(created_at) = ?', [Carbon::today()])->where('result', 1)->sum('amount');
           // $data['distributor_commission_today'] += $AepsWalletAction->where('user_id', $child['user_id'])->where('commission', 1)->whereRaw('date(created_at) = ?', [Carbon::today()])->sum('amount');
            
           //  $data['transactions_weekly'] += $AepsTransaction->where('user_id', $child['user_id'])->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])->where('result', 1)->count();
           //  $data['amount_weekly'] += $AepsTransaction->where('user_id', $child['user_id'])->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])->where('result', 1)->sum('amount');
           // // $data['distributor_commission_weekly'] += $AepsWalletAction->where('user_id', $child['user_id'])->where('commission', 1)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfWeek()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfWeek()])->sum('amount');
            
           //  $data['transactions_monthly'] += $AepsTransaction->where('user_id', $child['user_id'])->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])->where('result', 1)->count();
           //  $data['amount_monthly'] += $AepsTransaction->where('user_id', $child['user_id'])->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])->where('result', 1)->sum('amount');

            //$data['distributor_commission_monthly'] += $AepsWalletAction->where('user_id', $child['user_id'])->where('commission', 1)->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])->sum('amount');
        }
        //echo $data['transactions_monthly'];
        //exit;
        $data['childCount'] = sizeOf($children);
//dd(($data));
        if (Auth::user()->vendorDetails->type == 2)
            return View::make('home.index-distributor')->with($data);

        /*$agents = $Vendor->whereIn('parent_id', $Vendor->where('parent_id', Auth::user()->id)->lists('user_id')
                )->where('type', 1)->get();
        $data['agentBalance'] = 0;
        foreach ($agents as $agent) {
            $data['agentBalance'] += $agent['balance'];
        }
        $data['agentCount'] = sizeOf($agents);
        */
        if (Auth::user()->vendorDetails->type == 3)
            return View::make('home.index-super-distributor')->with($data);
        }

        if(Auth::user()->vendorDetails->type == 4){
            return View::make('home.index-sales-executive');
        }
        if(Auth::user()->vendorDetails->type == 5){
          return View::make('home.index-area-sales-officer');
        }
        if(Auth::user()->vendorDetails->type == 6){
          return View::make('home.index-area-sales-manager');
        }
        if(Auth::user()->vendorDetails->type == 7){
          return View::make('home.index-cluster-head');
        }
        if(Auth::user()->vendorDetails->type == 10){
          return View::make('home.index-state-head');
        }
        if(Auth::user()->vendorDetails->type == 11){
          return View::make('home.index-regional-head');
        } 
  }

  public function landing ()
  {
    return View::make('landing');
  }

  public function getServices ()
  {
    $data['aeps'] = "#";
    $data['dmt'] = getenv('DMT_URL');
    $data['cpt'] = "#";
    $data['irctc']="#";
    $data['indonepal']="#";
    $data['playwin']="#";
    Session::put('dmt_user',0);
    $permissions = ServicePermission::where('user_id', Session::get('user.user_id'))->lists('permission');
    if(null != $permissions){
      foreach($permissions as $per){
        if($per =='aeps')
          $data['aeps'] = getenv('AUTH_URL');
        if($per=='dmt'){
          $data['dmt'] = getenv('DMT_URL');
          Session::put('dmt_user',1);
        }
        if($per=='cpt')
          $data['cpt'] = getenv('CP_URL');
        if($per =='irctc')
          $data['irctc'] = getenv('IRCTC_URL');
        if($per=='indonepal')
          $data['indonepal'] = getenv('INDONEPAL_URL');
        if($per=='playwin')
          $data['playwin'] = getenv('PLAYWIN_URL');
      }
    }

$usersdata=User::where('phone_no',\Cookie::get('mobileno'))->first();

if($usersdata)
{
  $usersdatavendor=Vendor::where('user_id',$usersdata->id)->first();
  if($usersdatavendor)
  {

$data['portal_id'] =$usersdatavendor->portal_id;
  }else
  {
$data['portal_id'] ='V2';

  }

}else
{
$data['portal_id'] ='V2';

}



    
    return View::make('home.services')->with($data);
  }



}