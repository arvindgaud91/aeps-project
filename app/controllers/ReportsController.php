<?php
use Acme\Auth\Auth;
use Acme\Helper\Rabbit;
use Acme\Helper\GateKeeper;
use Carbon\Carbon;
use Acme\Helper\Export;

class ReportsController extends BaseController
{
  public function getDistributorCommissionReport(){
    Paginator::setPageName('page');
     $AepsTransaction = new  AepsTransaction;
      $AepsTransaction->setConnection('mysql1');

       $Vendor=new Vendor;
       $Vendor->setConnection('mysql1');
    if(Auth::user()->isSuperDistributor()){
           
           $distributor_userIds = $Vendor->where('parent_id', Auth::user()->id)->lists('user_id'); 
           $agentIds = $Vendor->whereIn('parent_id', $distributor_userIds)->lists('user_id'); 
       }
        if(Auth::user()->isDistributor()){
          $agentIds = $Vendor->where('parent_id', Auth::user()->id)->lists('user_id');
       }
       $commissionsObj = $AepsTransaction->leftjoin('users', 'aeps_transactions.user_id', '=', 'users.id')
       ->leftjoin('aeps_wallet_actions','aeps_transactions.id','=','aeps_wallet_actions.transaction_id')
       ->whereIn('aeps_transactions.user_id', $agentIds)
       ->where('aeps_wallet_actions.user_id',Auth::user()->id)
       ->where('aeps_wallet_actions.commission',1)
       ->select('users.name', 'users.phone_no','aeps_transactions.bank_id','aeps_transactions.created_at','aeps_transactions.amount','aeps_transactions.status','aeps_transactions.remarks','aeps_transactions.type','aeps_transactions.result','aeps_transactions.id','aeps_wallet_actions.amount as commission')->orderBy('aeps_transactions.id', 'DESC')->paginate(100);
       $commissions = $commissionsObj->getItems();
       return View::make('reports.distributor-commission-reports',['commissions' => $commissions,'commissionsObj' => $commissionsObj]);

  }

  public function getDistributorCommissionExport() {
    $AepsTransaction = new  AepsTransaction;
      $AepsTransaction->setConnection('mysql1');

       $Vendor=new Vendor;
       $Vendor->setConnection('mysql1');
      if(Auth::user()->isSuperDistributor()){
           
           $distributor_userIds = $Vendor->where('parent_id', Auth::user()->id)->lists('user_id'); 
           $agentIds = $Vendor->whereIn('parent_id', $distributor_userIds)->lists('user_id'); 
       }
        if(Auth::user()->isDistributor()){
          $agentIds = $Vendor->where('parent_id', Auth::user()->id)->lists('user_id'); 
          }
        if ((Input::get('from_date') && Input::get('to_date'))) {
            $start_date = date("Y-m-d", strtotime(Input::get('from_date')));
            $end_date = date("Y-m-d", strtotime(Input::get('to_date')));
            $start_date_time = $start_date.' 00:00:00';
            $end_date_time = $end_date.' 23:59:59';
            $records = $AepsTransaction->leftjoin('users', 'aeps_transactions.user_id', '=', 'users.id')->whereBetween('aeps_transactions.created_at', [$start_date_time, $end_date_time])
            ->leftjoin('aeps_wallet_actions','aeps_transactions.id','=','aeps_wallet_actions.transaction_id')
            ->whereIn('aeps_transactions.user_id', $agentIds)
            ->where('aeps_wallet_actions.user_id',Auth::user()->id)
       ->select('aeps_transactions.id as Transaction_Id','aeps_transactions.created_at as Transaction_Date',DB::raw("(if((aeps_transactions.status=4 or aeps_transactions.status=3 or aeps_transactions.status=0) and aeps_transactions.result=0, 'Failed', 'Success')) as Status,(CASE
         WHEN aeps_transactions.type=0 THEN 'Balance request'
         WHEN aeps_transactions.type=1 THEN 'Deposit request'
         WHEN aeps_transactions.type=2 THEN 'Withdraw request'
         WHEN aeps_transactions.type=3 THEN 'To Pay request'
         END) as Type"),'users.name as AgentName','aeps_transactions.amount as Amount','aeps_wallet_actions.amount as CommissionAmount')->orderBy('aeps_transactions.id', 'DESC')->get()->toArray();
        } 
        $export_csv= new Export();
        $export_csv->exportData($records,"distributor-commission-report-");
    }

}