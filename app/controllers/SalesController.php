<?php

use Acme\Auth\Auth;
use Carbon\Carbon;
use Acme\Helper\Export;
class SalesController extends BaseController {

    

    public function getAgentSalesReport(){
        Paginator::setPageName('page');
        $Vendor = new Vendor;
       $Vendor->setConnection('mysql1');
        if(Auth::user()->isSalesExecutive()){
            $parentId = $Vendor->where('asm_id',Auth::user()->id)->lists('user_id');
            $agentuserId = $Vendor->whereIn('parent_id',$parentId)
                                    ->where('type',1)
                                    ->whereNotNull('csr_id')
                                    ->lists('user_id');  
         $agentSalesObj =DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$agentuserId)
            ->paginate(100);
            $agentSales = $agentSalesObj->getItems();
            $sumOfAgentAmount[] = 0 ;
            foreach ($agentSales as $agent) {
                $sumOfAgentAmount[$agent->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->where('aeps_transactions.user_id',$agent->id)
                ->sum('aeps_transactions.amount');
            }  
            return View::make('reports.agent-sales-executive-reports',['agentSales' => $agentSales,'agentSalesObj' => $agentSalesObj,'agentSum'=>$sumOfAgentAmount]);
        }
    }

    public function getDistributorSalesReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isSalesExecutive()){
             $Vendor = new Vendor;
       $Vendor->setConnection('mysql1');
            $distributoruserId = $Vendor->where('asm_id',Auth::user()->id)
            ->where('type',2)
            ->lists('user_id');
            $distributorSalesObj =DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$distributoruserId)
            ->paginate(100);
            $distributorData=$distributorSalesObj->getItems();
            $sumOfAgentAmount[] = 0;
            $countOfAgent[] = 0;
            foreach ($distributorData as $distributor) {
                $agent_list=$Vendor->where('type',1)
            ->where('parent_id',$distributor->id)
            ->whereNotNull('csr_id')
            ->lists('user_id');

                $sumOfAgentAmount[$distributor->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->sum('aeps_transactions.amount');
                $countOfAgent[$distributor->id]=sizeof($agent_list);
            }  
        return View::make('reports.distributor-sales-executive-reports',['distributorSalesObj'=>$distributorSalesObj,'distributorSales' => $distributorData,'distributorAgentCount'=>$countOfAgent,'distributorAgentSum'=>$sumOfAgentAmount]);
        }
    }

    public function getDistributorSalesDateReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isSalesExecutive()){
             $Vendor = new Vendor;
       $Vendor->setConnection('mysql1');
            $distributoruserId = $Vendor->where('asm_id',Auth::user()->id)->lists('user_id');
            $distributorSalesObj =DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$distributoruserId)
            ->paginate(100);
            $distributorData=$distributorSalesObj->getItems();
            $sumOfAgentAmount[] = 0;
            $countOfAgent[] = 0;
            $distributorAgentFTDSum[] = 0;
            $distributorAgentLMTDSum[] = 0;
            $distributorAgentMTDSum[] = 0;
            foreach ($distributorData as $distributor) {
                $agent_list=$Vendor->where('type',1)
            ->where('parent_id',$distributor->id)
            ->whereNotNull('csr_id')
            ->lists('user_id');
            //dd(Carbon::parse('-1 month'));
                $distributorAgentFTDSum[$distributor->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('aeps_transactions.amount');
                $distributorAgentLMTDSum[$distributor->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('aeps_transactions.amount');
                $distributorAgentMTDSum[$distributor->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('aeps_transactions.amount');
                $countOfAgent[$distributor->id]=sizeof($agent_list);
            }  
        return View::make('reports.distributor-sales-executive-date-reports',['distributorSalesObj'=>$distributorSalesObj,'distributorSales' => $distributorData,'distributorAgentCount'=>$countOfAgent,'distributorAgentFTDSum'=>$distributorAgentFTDSum,'distributorAgentLMTDSum'=>$distributorAgentLMTDSum,'distributorAgentMTDSum'=>$distributorAgentMTDSum]);
        }
    }

     public function getAgentSalesReportForDistributor($id){
       Paginator::setPageName('page');
        if(Auth::user()->isSalesExecutive()){
            $Vendor = new Vendor;
       $Vendor->setConnection('mysql1');
            $parentId = $Vendor->where('parent_id',$id)
            ->where('type',1)
            ->whereNotNull('csr_id')
            ->lists('user_id');

            // $agentuserId = $Vendor->whereIn('parent_id',$parentId)
            //                         ->where('type',1)
            //                         ->lists('user_id');  
         $agentSalesObj =DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$parentId)
            ->paginate(100);
            $agentSales = $agentSalesObj->getItems();
            $sumOfAgentAmount[] = 0 ;
            foreach ($agentSales as $agent) {
                $sumOfAgentAmount[$agent->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->where('aeps_transactions.user_id',$agent->id)
                ->sum('aeps_transactions.amount');
            }  
            return View::make('reports.agent-sales-executive-reports-for-distributor',['agentSales' => $agentSales,'agentSalesObj' => $agentSalesObj,'agentSum'=>$sumOfAgentAmount]);
        }
    }

    public function getAgentExport(){
        if(Auth::user()->isSalesExecutive()){
            $Vendor = new Vendor;
       $Vendor->setConnection('mysql1');
            $parentId = $Vendor->where('asm_id',Auth::user()->id)->lists('user_id');
            $agentId = $Vendor->where('type',1)
            ->whereIn('parent_id',$parentId)
            ->lists('user_id'); 
            $records = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->join('aeps_transactions','aeps_transactions.user_id','=','user_vendors.user_id')
            ->whereIn('user_vendors.user_id',$agentId)
            ->select('user_vendors.user_id as AgentID', 'users.name as AgentName','user_vendors.csr_id as Code','aeps_transactions.amount', DB::raw('SUM(aeps_transactions.amount) as amount'))
            ->groupBy('aeps_transactions.user_id')
            ->get();
        $datas= json_decode( json_encode($records), true); 
        $export_csv= new Export();
        $export_csv->exportData($datas,"agent-sales-report-");  
        }
    }

    
    public function getDistributorExport(){
        if(Auth::user()->isSalesExecutive()){
             $Vendor = new Vendor;
       $Vendor->setConnection('mysql1');
            $distributoruserId = $Vendor->where('type',2)
            ->where('asm_id',Auth::user()->id)
            ->lists('user_id'); 
            //dd(implode(",",$distributoruserId));
            $records =DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->whereIn('user_vendors.user_id',$distributoruserId)
            ->select('users.name as Distributor_Name','user_vendors.csr_id as Code','users.phone_no as Mobile_No')
            ->get();
            //dd($records);
        $datas= json_decode( json_encode($records), true); 
        $export_csv= new Export();
        $export_csv->exportData($datas,"distributor-sales-report-");
        }
    }




    // public function getDistributorExport(){
    //     if(Auth::user()->isSalesExecutive()){
    //         $state = $Vendor->where('user_id',Auth::user()->id)->pluck('state');
    //         $distributorId = $Vendor->where('type',2)->lists('user_id');  
    //     }
    //     $records = DB::connection('mysql1')->table('user_vendors')
    //         ->join('users','users.id','=','user_vendors.user_id')
    //         ->join('aeps_transactions','aeps_transactions.user_id','=','user_vendors.user_id')
    //         ->select('*', DB::connection('mysql1')->raw('SUM(aeps_transactions.amount) as amount'))
    //         ->groupBy('aeps_transactions.user_id')
    //         ->whereIn('user_vendors.user_id',$distributorId)
    //         ->where('user_vendors.state',$state)
    //         ->get();
    //         $distributorAgentCount;
    //         $distributorAgentSum;
    //         foreach($distributorId  as $distributor)
    //         {
    //             $distributorAgentCount[$distributor]=DB::connection('mysql1')->table('user_vendors')
    //         ->where('user_vendors.type',1)
    //         ->where('user_vendors.parent_id',$distributor)
    //         ->count('user_vendors.user_id');

    //         $agentIds = $Vendor->where('type',1)
    //         ->where('parent_id',$distributor)->lists('user_id');
    //             $distributorAgentSum[$distributor]=DB::connection('mysql1')->table('aeps_transactions')
    //             ->whereIn('aeps_transactions.user_id',$agentIds)
    //             ->sum('aeps_transactions.amount');
    //         }
    //          $records->agentAmount=DB::connection('mysql1')->table('user_vendors')
    //         ->join('aeps_transactions','aeps_transactions.user_id','=','user_vendors.user_id')
    //         ->whereIn('user_vendors.parent_id',$distributorId)
    //         ->select('*', DB::connection('mysql1')->raw('SUM(aeps_transactions.amount) as amount'))->get();

    //         $datas= json_decode( json_encode($records), true); 
    //         $export_csv= new Export();
    //         $export_csv->exportData($datas,"distributor-sales-report-");
    // }

   /* public function getStateHeadSalesReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead()){
            $distributoruserId = $Vendor->where('asm_id',Auth::user()->id)
            ->where('type',2)
            ->lists('user_id');
            $distributorSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$distributoruserId)
            ->paginate(100);
            $distributorData=$distributorSalesObj->getItems();
            $sumOfAgentAmount[] = 0;
            $countOfAgent[] = 0;
            foreach ($distributorData as $distributor) {
                $agent_list=$Vendor->where('type',1)
            ->where('parent_id',$distributor->id)
            ->whereNotNull('csr_id')
            ->lists('user_id');

                $sumOfAgentAmount[$distributor->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->sum('aeps_transactions.amount');
                $countOfAgent[$distributor->id]=sizeof($agent_list);
            }  
        return View::make('reports.state-head-reports-for-regional-head',['distributorSalesObj'=>$distributorSalesObj,'distributorSales' => $distributorData,'distributorAgentCount'=>$countOfAgent,'distributorAgentSum'=>$sumOfAgentAmount]);
        }
    }*/

    /*****************************Area Sales Officer Start*****************************/

    /*public function getDistributorExport(){
        if(Auth::user()->isAreaSalesOfficer()){

            $salesExecutiveuserId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->where('type',2)
            ->where('asm_id',Auth::user()->id)
            ->lists('user_id'); 
            //dd(implode(",",$distributoruserId));
            $records = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->whereIn('user_vendors.user_id',$distributoruserId)
            ->select('users.name as Distributor_Name','user_vendors.csr_id as Code','users.phone_no as Mobile_No')
            ->get();
            dd($records);
        $datas= json_decode( json_encode($records), true); 
        $export_csv= new Export();
        $export_csv->exportData($datas,"distributor-sales-report-");
        }
    }*/

    public function getDistributorAgentExport($id){
        if(Auth::user()->isRegionalHead() || Auth::user()->isStateHead() || Auth::user()->isClusterHead() || Auth::user()->isAreaSalesManager() || Auth::user()->isAreaSalesOfficer()){
            $Vendor = new Vendor;
       $Vendor->setConnection('mysql1');
            $agentId = $Vendor->where('parent_id',$id)
            ->where('type',1)
            ->whereNotNull('csr_id')
            ->lists('user_id');

            $records =DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->join('aeps_transactions','aeps_transactions.user_id','=','user_vendors.user_id')
            ->whereIn('user_vendors.user_id',$agentId)
            ->select('user_vendors.user_id as AgentId','users.name as AgentName','user_vendors.csr_id as Code','aeps_transactions.amount',DB::connection('mysql1')->raw('SUM(aeps_transactions.amount) as amount'))
            ->groupBy('aeps_transactions.user_id')
            ->get();
            $datas= json_decode( json_encode($records), true); 
            $export_csv= new Export();
            $export_csv->exportData($datas,"agent-sales-report-");
        }
    }

    public function getAreaSalesOfficerReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isAreaSalesOfficer()){

            $salesExecutiveuserId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',4)
            ->lists('user_id');

            //dd(Auth::user()->id);   

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

           $salesExecutiveSalesObj =DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$salesExecutiveuserId)
            ->paginate(100);

            $salesExecutiveData=$salesExecutiveSalesObj->getItems();
            $countOfDistributor[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($salesExecutiveData as $salesexecutive) {
                $distributor_list=$Vendor->where('type',2)
                ->where('asm_id',$salesexecutive->id)
                ->lists('user_id');
                $countOfDistributor[$salesexecutive->id]=sizeof($distributor_list);

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$salesexecutive->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->sum('aeps_transactions.amount');
            }

            return View::make('reports.sales-executive-area-sales-officer-reports',['salesExecutiveSalesObj'=>$salesExecutiveSalesObj,'salesExecutiveSales' => $salesExecutiveData, 'countOfDistributor'=>$countOfDistributor, 'sumOfAgentAmount'=>$sumOfAgentAmount]);
        }
    }

    
    public function getDistributorAreaSalesOfficerReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isAreaSalesOfficer()){

            $salesExecutiveuserId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

           $distributorSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$distributoruserId)
            ->paginate(100);

            $distributorSales=$distributorSalesObj->getItems();

            $countOfAgent[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($distributorSales as $distributorSale) {

                $agent_list=$Vendor->where('type',1)
                ->where('parent_id',$distributorSale->id)
                ->whereNotNull('csr_id')
                ->lists('user_id');
                $sumOfAgentAmount[$distributorSale->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->sum('aeps_transactions.amount');
                $countOfAgent[$distributorSale->id]=sizeof($agent_list);
            } 

            return View::make('reports.distributor-area-sales-officer-reports',['distributorSales' => $distributorSales,'distributorSalesObj' => $distributorSalesObj, 'countOfAgent'=>$countOfAgent,'agentSum'=>$sumOfAgentAmount]);
        }
    }

    public function getSalesExecutiveReportForAreaSalesOfficer($id){
       Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead() || Auth::user()->isStateHead() || Auth::user()->isClusterHead() || Auth::user()->isAreaSalesManager() || Auth::user()->isAreaSalesOfficer()){
            $parentId = $Vendor->where('asm_id',$id)
            ->where('type',2)
            ->lists('user_id');
  
            $distributorSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id')
            ->whereIn('user_vendors.user_id',$parentId)
            ->paginate(100);
            $distributorSales = $distributorSalesObj->getItems();   
            $sumOfAgentAmount[] = 0;
            $countOfAgent[] = 0;
            foreach ($distributorSales as $distributorSale) {


                $agent_list=$Vendor->where('type',1)
                ->where('parent_id',$distributorSale->id)
                ->whereNotNull('csr_id')
                ->lists('user_id');

                $countOfAgent[$distributorSale->id]=sizeof($agent_list);


                $sumOfAgentAmount[$distributorSale->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->sum('aeps_transactions.amount');
                //dd($sumOfAgentAmount);
            } 
            return View::make('reports.sales-executive-reports-for-area-sales-officer',['distributorSales' => $distributorSales, 'countOfAgent'=>$countOfAgent,'distributorSalesObj' => $distributorSalesObj,'agentSum'=>$sumOfAgentAmount]);
        }
    }

    public function getAgentReportForAreaSalesOfficer($id){
       Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead() || Auth::user()->isStateHead() || Auth::user()->isClusterHead() || Auth::user()->isAreaSalesManager() || Auth::user()->isAreaSalesOfficer()){
            $agentuserId = $Vendor->where('parent_id',$id)
            ->where('type',1)
            ->whereNotNull('csr_id')
            ->lists('user_id');

            $agentSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$agentuserId)
            ->paginate(100);
            $agentSales = $agentSalesObj->getItems();
            $sumOfAgentAmount[] = 0 ;
            foreach ($agentSales as $agent) {
                $sumOfAgentAmount[$agent->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->where('aeps_transactions.user_id',$agent->id)
                ->sum('aeps_transactions.amount');
            }  
            return View::make('reports.agent-reports-for-area-sales-officer',['agentSales' => $agentSales,'agentSalesObj' => $agentSalesObj,'agentSum'=>$sumOfAgentAmount, 'parentId'=>$id]);
        }
    }

    public function getSalesExecutiveSalesDateReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isAreaSalesOfficer()){

            $salesExecutiveuserId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

           $salesExecutiveSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$salesExecutiveuserId)
            ->paginate(100);
            $i=0;
            $salesExecutiveData=$salesExecutiveSalesObj->getItems();
            $salesExecutiveFTDSum[] = 0;
            $salesExecutiveLMTDSum[] = 0;
            $salesExecutiveMTDSum[] = 0;
            $countOfDistributor[] = 0;
            foreach ($salesExecutiveData as $salesexecutive) {
                $distributor_list=$Vendor->where('type',2)
                ->where('asm_id',$salesexecutive->id)
                ->lists('user_id');
                $countOfDistributor[$salesexecutive->id]=sizeof($distributor_list);

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $salesExecutiveFTDSum[$salesexecutive->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('aeps_transactions.amount');

                $salesExecutiveLMTDSum[$salesexecutive->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('aeps_transactions.amount');

                $salesExecutiveMTDSum[$salesexecutive->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('aeps_transactions.amount');

                
            }

            
        return View::make('reports.sales-executive-area-sales-officer-date-reports',['salesExecutiveSalesObj'=>$salesExecutiveSalesObj,'salesExecutiveSales' => $salesExecutiveData,'countOfDistributor'=>$countOfDistributor,'salesExecutiveFTDSum'=>$salesExecutiveFTDSum,'salesExecutiveLMTDSum'=>$salesExecutiveLMTDSum,'salesExecutiveMTDSum'=>$salesExecutiveMTDSum]);
        }
    }

    public function getDistributorSalesExecutiveSalesDateReport($id){
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead() || Auth::user()->isStateHead() || Auth::user()->isClusterHead() || Auth::user()->isAreaSalesManager() || Auth::user()->isAreaSalesOfficer()){

            $parentId = $Vendor->where('asm_id',$id)
            ->where('type',2)
            ->lists('user_id');
 
            $distributorSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$parentId)
            ->paginate(100);
            $distributorData=$distributorSalesObj->getItems();

            $sumOfAgentAmount[] = 0;
            $countOfAgent[] = 0;
            $distributorFTDSum[] = 0;
            $distributorLMTDSum[] = 0;
            $distributorMTDSum[] = 0;

            foreach ($distributorData as $distributor) {
                $agent_list=$Vendor->where('type',1)
                ->where('parent_id',$distributor->id)
                ->whereNotNull('csr_id')
                ->lists('user_id');
            //dd(Carbon::parse('-1 month'));
                $distributorFTDSum[$distributor->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('aeps_transactions.amount');
                $distributorLMTDSum[$distributor->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('aeps_transactions.amount');
                $distributorMTDSum[$distributor->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('aeps_transactions.amount');
                $countOfAgent[$distributor->id]=sizeof($agent_list);
            }  
        return View::make('reports.distributor-date-report-for-area-sales-officer',['distributorSalesObj'=>$distributorSalesObj,'distributorSales' => $distributorData,'distributorAgentCount'=>$countOfAgent,'distributorFTDSum'=>$distributorFTDSum,'distributorLMTDSum'=>$distributorLMTDSum,'distributorMTDSum'=>$distributorMTDSum]);
        }
    }

    public function getAgentDistributorSalesDateReport($id){
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead() || Auth::user()->isStateHead() || Auth::user()->isClusterHead() || Auth::user()->isAreaSalesManager() || Auth::user()->isAreaSalesOfficer() || Auth::user()->isSalesExecutive()){

 
            $agentSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->where('user_vendors.parent_id',$id)
            ->whereNotNull('csr_id')
            ->paginate(100);
            $agentData=$agentSalesObj->getItems();

            $sumOfAgentAmount[] = 0;
            $agentFTDSum[] = 0;
            $agentLMTDSum[] = 0;
            $agentMTDSum[] = 0;
            foreach ($agentData as $agent) {
                $agentFTDSum[$agent->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->where('aeps_transactions.user_id',$agent->id)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('aeps_transactions.amount');
                $agentLMTDSum[$agent->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->where('aeps_transactions.user_id',$agent->id)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('aeps_transactions.amount');
                $agentMTDSum[$agent->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->where('aeps_transactions.user_id',$agent->id)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('aeps_transactions.amount');
            }  
        return View::make('reports.agent-distributor-date-report-for-area-sales-officer',['agentSalesObj'=>$agentSalesObj,'agentSales' => $agentData, 'agentFTDSum'=>$agentFTDSum,'agentLMTDSum'=>$agentLMTDSum,'agentMTDSum'=>$agentMTDSum]);
        }
    }

    /*****************************Area Sales Manager Start*****************************/
    
    public function getAreaSalesManagerReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isAreaSalesManager()){

            $areaSalesOfficerId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areaSalesOfficerSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$areaSalesOfficerId)
            ->paginate(100);

            $areaSalesOfficerData=$areaSalesOfficerSalesObj->getItems();

            //dd($areaSalesOfficerData);

            $countOfSalesExecutive[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($areaSalesOfficerData as $areaSalesOfficer) {
                    $sales_executive_list=$Vendor->where('type',4)
                ->where('parent_id',$areaSalesOfficer->id)
                ->lists('user_id');
                $countOfSalesExecutive[$areaSalesOfficer->id]=sizeof($sales_executive_list);
                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$areaSalesOfficer->id]=DB::connection('mysql1')->table('aeps_transactions')
                    ->whereIn('aeps_transactions.user_id',$agent_list)
                    ->sum('aeps_transactions.amount');
            }

            return View::make('reports.area-salaes-officer-area-sales-manager-reports',['areaSalesOfficerSalesObj'=>$areaSalesOfficerSalesObj,'areaSalesOfficerSales' => $areaSalesOfficerData, 'countOfSalesExecutive'=>$countOfSalesExecutive, 'sumOfAgentAmount'=>$sumOfAgentAmount]);
        } 
    }

    public function getSalesExecutiveSalesManagerReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isAreaSalesManager()){

            $areaSalesOfficerId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

           $salesExecutiveSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$salesExecutiveuserId)
            ->paginate(100);

            $salesExecutiveData=$salesExecutiveSalesObj->getItems();
            $countOfDistributor[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($salesExecutiveData as $salesexecutive) {
                $distributor_list=$Vendor->where('type',2)
                ->where('asm_id',$salesexecutive->id)
                ->lists('user_id');
                $countOfDistributor[$salesexecutive->id]=sizeof($distributor_list);

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$salesexecutive->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->sum('aeps_transactions.amount');
            }

            return View::make('reports.sales-executive-area-sales-manager-reports',['salesExecutiveSalesObj'=>$salesExecutiveSalesObj,'salesExecutiveSales' => $salesExecutiveData, 'countOfDistributor'=>$countOfDistributor, 'sumOfAgentAmount'=>$sumOfAgentAmount]);
        }
    }
    
    public function getSalesOfficeAreaSalesManagerSalesDateReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isAreaSalesManager()){

            $areaSalesOfficerId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',5)
            ->lists('user_id');

            //dd($areaSalesOfficerId);

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areaSalesOfficerSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$areaSalesOfficerId)
            ->paginate(100);

            $areaSalesOfficerData=$areaSalesOfficerSalesObj->getItems();
            $countOfSalesExecutive[] = 0;
            $areaSalesOfficerFTDSum[] =0;
            $areaSalesOfficerLMTDSum[]=0;
            $areaSalesOfficerMTDSum[]=0;
            foreach ($areaSalesOfficerData as $areaSalesOfficer) {
                $sales_executive_list=$Vendor->where('type',4)
                ->where('parent_id',$areaSalesOfficer->id)
                ->lists('user_id');

                $countOfSalesExecutive[$areaSalesOfficer->id]=sizeof($sales_executive_list);

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $areaSalesOfficerFTDSum[$areaSalesOfficer->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('aeps_transactions.amount');

                $areaSalesOfficerLMTDSum[$areaSalesOfficer->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('aeps_transactions.amount');

                $areaSalesOfficerMTDSum[$areaSalesOfficer->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('aeps_transactions.amount');
            }

        return View::make('reports.area-sales-officer-area-sales-manager-date-reports',['areaSalesOfficerSalesObj'=>$areaSalesOfficerSalesObj,'areaSalesOfficerSales' => $areaSalesOfficerData,'countOfSalesExecutive'=>$countOfSalesExecutive,'areaSalesOfficerFTDSum'=>$areaSalesOfficerFTDSum,'areaSalesOfficerLMTDSum'=>$areaSalesOfficerLMTDSum,'areaSalesOfficerMTDSum'=>$areaSalesOfficerMTDSum]);
        }
    }

    public function getSalesExecutiveReportForAreaSalesManager($id){
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead() || Auth::user()->isStateHead() || Auth::user()->isClusterHead() || Auth::user()->isAreaSalesManager()){


            $salesExecutiveuserId = $Vendor->where('parent_id',$id)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

           $salesExecutiveSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$salesExecutiveuserId)
            ->paginate(100);

            $salesExecutiveData=$salesExecutiveSalesObj->getItems();
            $countOfDistributor[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($salesExecutiveData as $salesexecutive) {
                $distributor_list=$Vendor->where('type',2)
            ->where('asm_id',$salesexecutive->id)
            ->lists('user_id');
            $countOfDistributor[$salesexecutive->id]=sizeof($distributor_list);

            $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

            $sumOfAgentAmount[$salesexecutive->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->sum('aeps_transactions.amount');

            }

            return View::make('reports.sales-executive-area-sales-officer-reports',['salesExecutiveSalesObj'=>$salesExecutiveSalesObj,'salesExecutiveSales' => $salesExecutiveData, 'countOfDistributor'=>$countOfDistributor, 'sumOfAgentAmount'=>$sumOfAgentAmount]);
        }
    }

    public function getSalesExecutiveAreaSalesManagerSalesDateReport($id){
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead() || Auth::user()->isStateHead() || Auth::user()->isClusterHead() || Auth::user()->isAreaSalesManager()){
            $salesExecutiveuserId = $Vendor->where('parent_id',$id)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

           $salesExecutiveSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$salesExecutiveuserId)
            ->paginate(100);

            $salesExecutiveData=$salesExecutiveSalesObj->getItems();
            $countOfDistributor[] = 0;
            $salesExecutiveDistributorFTDSum[] = 0;
            $salesExecutiveDistributorLMTDSum[] = 0;
            $salesExecutiveDistributorMTDSum[] = 0;
        foreach ($salesExecutiveData as $salesexecutive) {
                $distributor_list=$Vendor->where('type',2)
            ->where('asm_id',$salesexecutive->id)
            ->lists('user_id');
            $countOfDistributor[$salesexecutive->id]=sizeof($distributor_list);

            $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');


                $salesExecutiveFTDSum[$salesexecutive->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('aeps_transactions.amount');

                $salesExecutiveLMTDSum[$salesexecutive->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('aeps_transactions.amount');

                $salesExecutiveMTDSum[$salesexecutive->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('aeps_transactions.amount');
            } 

             
        return View::make('reports.sales-executive-area-sales-officer-date-reports',['salesExecutiveSalesObj'=>$salesExecutiveSalesObj,'salesExecutiveSales' => $salesExecutiveData,'countOfDistributor'=>$countOfDistributor,'salesExecutiveFTDSum'=>$salesExecutiveFTDSum,'salesExecutiveLMTDSum'=>$salesExecutiveLMTDSum,'salesExecutiveMTDSum'=>$salesExecutiveMTDSum]);
        }
    }


    /*****************************Cluster Head Start*****************************/
    public function getClusterHeadReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isClusterHead()){

            $areaSalesManagerId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = $Vendor->whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areaSalesManagerSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$areaSalesManagerId)
            ->paginate(100);

            $areaSalesManagerData=$areaSalesManagerSalesObj->getItems();

            //dd($areaSalesOfficerData);

            $countOfSalesOfficer[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($areaSalesManagerData as $areaSalesManager) {
                $area_sales_officer_list=$Vendor->where('type',5)
                ->where('parent_id',$areaSalesManager->id)
                ->lists('user_id');
                $countOfSalesOfficer[$areaSalesManager->id]=sizeof($area_sales_officer_list);

                $sales_executive_list=$Vendor->where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$areaSalesManager->id]=DB::connection('mysql1')->table('aeps_transactions')
                    ->whereIn('aeps_transactions.user_id',$agent_list)
                    ->sum('aeps_transactions.amount');

            }

            
            return View::make('reports.area-salaes-manager-cluster-head-reports',['areaSalesManagerSalesObj'=>$areaSalesManagerSalesObj,'areaSalesManagerSales' => $areaSalesManagerData, 'countOfSalesOfficer'=>$countOfSalesOfficer, 'sumOfAgentAmount'=>$sumOfAgentAmount]);
        } 
    }
    
    public function getAreaSalesOfficerClustorHeadReport($id){
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead() || Auth::user()->isStateHead() || Auth::user()->isClusterHead()){

            $areaSalesOfficerId = $Vendor->where('parent_id',$id)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areaSalesOfficerSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$areaSalesOfficerId)
            ->paginate(100);

            $areaSalesOfficerData=$areaSalesOfficerSalesObj->getItems();

            //dd($areaSalesOfficerData);

            $countOfSalesExecutive[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($areaSalesOfficerData as $areaSalesOfficer) {

                $sales_executive_list=$Vendor->where('type',4)
                ->where('parent_id',$areaSalesOfficer->id)
                ->lists('user_id');
                $countOfSalesExecutive[$areaSalesOfficer->id]=sizeof($sales_executive_list);

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$areaSalesOfficer->id]=DB::connection('mysql1')->table('aeps_transactions')
                    ->whereIn('aeps_transactions.user_id',$agent_list)
                    ->sum('aeps_transactions.amount');

            } 

            
            return View::make('reports.area-salaes-officer-area-sales-manager-reports',['areaSalesOfficerSalesObj'=>$areaSalesOfficerSalesObj,'areaSalesOfficerSales' => $areaSalesOfficerData, 'countOfSalesExecutive'=>$countOfSalesExecutive, 'sumOfAgentAmount'=>$sumOfAgentAmount]);
        } 
    }

    public function getSalesManagerClusterHeadSalesDateReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isClusterHead()){

           $areaSalesManagerId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = $Vendor->whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areaSalesManagerSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$areaSalesManagerId)
            ->paginate(100);

            $areaSalesManagerData=$areaSalesManagerSalesObj->getItems();

            //dd($areaSalesOfficerData);

            $countOfSalesOfficer[] = 0;
            $areaSalesManagerFTDSum[] = 0;
            $areaSalesManagerLMTDSum[] = 0;
            $areaSalesManagerMTDSum[] = 0;
            foreach ($areaSalesManagerData as $areaSalesManager) {
                $area_sales_officer_list=$Vendor->where('type',5)
                ->where('parent_id',$areaSalesManager->id)
                ->lists('user_id');
                $countOfSalesOfficer[$areaSalesManager->id]=sizeof($area_sales_officer_list);

                $sales_executive_list=$Vendor->where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $areaSalesManagerFTDSum[$areaSalesManager->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('aeps_transactions.amount');

                $areaSalesManagerLMTDSum[$areaSalesManager->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('aeps_transactions.amount');

                $areaSalesManagerMTDSum[$areaSalesManager->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('aeps_transactions.amount');
            }

            
 
        return View::make('reports.area-sales-manager-cluster-head-date-reports',['areaSalesManagerSalesObj'=>$areaSalesManagerSalesObj,'areaSalesManagerSales' => $areaSalesManagerData,'countOfSalesOfficer'=>$countOfSalesOfficer,'areaSalesManagerFTDSum'=>$areaSalesManagerFTDSum,'areaSalesManagerLMTDSum'=>$areaSalesManagerLMTDSum,'areaSalesManagerMTDSum'=>$areaSalesManagerMTDSum]);
        }
    }

    public function getSalesOfficeClusterHeadDateReport($id){
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead() || Auth::user()->isStateHead() || Auth::user()->isClusterHead()){

            $areaSalesOfficerId = $Vendor->where('parent_id',$id)
            ->where('type',5)
            ->lists('user_id');

            //dd($areaSalesOfficerId);

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

           $areaSalesOfficerSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$areaSalesOfficerId)
            ->paginate(100);
            $areaSalesOfficerData=$areaSalesOfficerSalesObj->getItems();
            $areaSalesOfficerFTDSum[] = 0;
            $areaSalesOfficerLMTDSum[] = 0;
            $areaSalesOfficerMTDSum[] = 0;
            $countOfSalesExecutive[] = 0;
            
        foreach ($areaSalesOfficerData as $areaSalesOfficer) {
            $sales_executive_list=$Vendor->where('type',4)
            ->where('parent_id',$areaSalesOfficer->id)
            ->lists('user_id');
            $countOfSalesExecutive[$areaSalesOfficer->id]=sizeof($sales_executive_list);

            $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

            $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

            $areaSalesOfficerFTDSum[$areaSalesOfficer->id]=DB::connection('mysql1')->table('aeps_transactions')
            ->whereIn('aeps_transactions.user_id',$agent_list)
            ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
            ->whereRaw('date(created_at) < ?', [Carbon::today()])
            ->sum('aeps_transactions.amount');

            $areaSalesOfficerLMTDSum[$areaSalesOfficer->id]=DB::connection('mysql1')->table('aeps_transactions')
            ->whereIn('aeps_transactions.user_id',$agent_list)
            ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
            ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
            ->sum('aeps_transactions.amount');

            $areaSalesOfficerMTDSum[$areaSalesOfficer->id]=DB::connection('mysql1')->table('aeps_transactions')
            ->whereIn('aeps_transactions.user_id',$agent_list)
            ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
            ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
            ->sum('aeps_transactions.amount');
        }

              
        return View::make('reports.area-sales-officer-area-sales-manager-date-reports',['areaSalesOfficerSalesObj'=>$areaSalesOfficerSalesObj,'areaSalesOfficerSales' => $areaSalesOfficerData,'countOfSalesExecutive'=>$countOfSalesExecutive,'areaSalesOfficerFTDSum'=>$areaSalesOfficerFTDSum,'areaSalesOfficerLMTDSum'=>$areaSalesOfficerLMTDSum,'areaSalesOfficerMTDSum'=>$areaSalesOfficerMTDSum]);
        }
    }

    public function getAreaSalesOfficerForClustorHeadReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isClusterHead()){

            $areaSalesManagerId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = $Vendor->whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areaSalesOfficerSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$areaSalesOfficerId)
            ->paginate(100);

            $areaSalesOfficerData=$areaSalesOfficerSalesObj->getItems();

            //dd($areaSalesOfficerData);

            $countOfSalesExecutive[] = 0;
            foreach ($areaSalesOfficerData as $areaSalesOfficer) {
                $sales_executive_list=$Vendor->where('type',4)
                ->where('parent_id',$areaSalesOfficer->id)
                ->lists('user_id');
                $countOfSalesExecutive[$areaSalesOfficer->id]=sizeof($sales_executive_list);

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$areaSalesOfficer->id]=DB::connection('mysql1')->table('aeps_transactions')
                    ->whereIn('aeps_transactions.user_id',$agent_list)
                    ->sum('aeps_transactions.amount');
            }
            
            return View::make('reports.area-salaes-officer-area-sales-manager-reports',['areaSalesOfficerSalesObj'=>$areaSalesOfficerSalesObj,'areaSalesOfficerSales' => $areaSalesOfficerData, 'countOfSalesExecutive'=>$countOfSalesExecutive, 'sumOfAgentAmount'=>$sumOfAgentAmount]);
        } 
    }

    /*****************************State Head Start*****************************/
    public function getStateHeadReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isStateHead()){

            $clusterHeadReportId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = $Vendor->whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = $Vendor->whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $clusterHeadSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$clusterHeadReportId)
            ->paginate(100);

            $clusterHeadData=$clusterHeadSalesObj->getItems();

            $countOfSalesManager[] = 0;
            foreach ($clusterHeadData as $clusterHead) {

                $area_sales_manager_list=$Vendor->where('type',6)
                ->where('parent_id',$clusterHead->id)
                ->lists('user_id');
                $countOfSalesManager[$clusterHead->id]=sizeof($area_sales_manager_list);

                $area_sales_officer_list=$Vendor->where('type',5)
                ->whereIn('parent_id',$area_sales_manager_list)
                ->lists('user_id');

                $sales_executive_list=$Vendor->where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$clusterHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->sum('aeps_transactions.amount');

            }
 
            return View::make('reports.cluster-head-state-head-reports',['clusterHeadSalesObj'=>$clusterHeadSalesObj,'clusterHeadSales' => $clusterHeadData, 'countOfSalesManager'=>$countOfSalesManager, 'sumOfAgentAmount'=>$sumOfAgentAmount]);
        } 
    }

    public function getAreaSalesManagerForStateHeadReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isStateHead()){

            $clusterHeadReportId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = $Vendor->whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = $Vendor->whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areaSalesManagerSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$areaSalesManagerId)
            ->paginate(100);

            $areaSalesManagerData=$areaSalesManagerSalesObj->getItems();

            //dd($areaSalesOfficerData);
            $sumOfAgentAmount[] = 0;
            $countOfAreaSalesOfficer[] = 0;
            foreach ($areaSalesManagerData as $areaSalesManager) {
                $area_sales_officer_list=$Vendor->where('type',5)
                ->where('parent_id',$areaSalesManager->id)
                ->lists('user_id');
                $countOfAreaSalesOfficer[$areaSalesManager->id]=sizeof($area_sales_officer_list);

                $sales_executive_list=$Vendor->where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$areaSalesManager->id]=DB::connection('mysql1')->table('aeps_transactions')
                    ->whereIn('aeps_transactions.user_id',$agent_list)
                    ->sum('aeps_transactions.amount');
            }

            return View::make('reports.area-sales-manager-reports-for-state-head',['areaSalesManagerSalesObj'=>$areaSalesManagerSalesObj,'areaSalesManagerSales' => $areaSalesManagerData, 'countOfAreaSalesOfficer'=>$countOfAreaSalesOfficer, 'sumOfAgentAmount'=>$sumOfAgentAmount]);
        } 
    }

    public function getAreaSalesManagerForClusterHeadReport($id){
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead() || Auth::user()->isStateHead()){

            $areaSalesManagerId = $Vendor->where('parent_id',$id)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = $Vendor->whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areaSalesManagerSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$areaSalesManagerId)
            ->paginate(100);

            $areaSalesManagerData=$areaSalesManagerSalesObj->getItems();

            $countOfAreaSalesOfficer[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($areaSalesManagerData as $areaSalesManager) {
                    $area_sales_officer_list=$Vendor->where('type',5)
                ->where('parent_id',$areaSalesManager->id)
                ->lists('user_id');

                $countOfAreaSalesOfficer[$areaSalesManager->id]=sizeof($area_sales_officer_list);

                $sales_executive_list=$Vendor->where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$areaSalesManager->id]=DB::connection('mysql1')->table('aeps_transactions')
                    ->whereIn('aeps_transactions.user_id',$agent_list)
                    ->sum('aeps_transactions.amount');

            }
            
            return View::make('reports.area-sales-manager-reports-for-state-head',['areaSalesManagerSalesObj'=>$areaSalesManagerSalesObj,'areaSalesManagerSales' => $areaSalesManagerData, 'countOfAreaSalesOfficer'=>$countOfAreaSalesOfficer, 'sumOfAgentAmount'=>$sumOfAgentAmount]);
        } 
    }

    public function getClusterHeadStateHeadSalesDateReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isStateHead()){

            

           $clusterHeadReportId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = $Vendor->whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = $Vendor->whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $clusterHeadSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$clusterHeadReportId)
            ->paginate(100);

            $clusterHeadSalesData=$clusterHeadSalesObj->getItems();

            //dd($areaSalesOfficerData);
            $clusterHeadFTDSum[] = 0;
            $clusterHeadLMTDSum[] = 0;
            $clusterHeadMTDSum[] = 0;
            $countOfSalesManager[] = 0;
            foreach ($clusterHeadSalesData as $clusterHead) {
                $area_sales_manager_list=$Vendor->where('type',6)
                ->where('parent_id',$clusterHead->id)
                ->lists('user_id');
                $countOfSalesManager[$clusterHead->id]=sizeof($area_sales_manager_list);

                $area_sales_officer_list=$Vendor->where('type',5)
                ->whereIn('parent_id',$area_sales_manager_list)
                ->lists('user_id');

                $sales_executive_list=$Vendor->where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $clusterHeadFTDSum[$clusterHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('aeps_transactions.amount');

                $clusterHeadLMTDSum[$clusterHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('aeps_transactions.amount');

                $clusterHeadMTDSum[$clusterHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('aeps_transactions.amount');

            }

        return View::make('reports.cluster-head-state-head-date-reports',['clusterHeadSalesObj'=>$clusterHeadSalesObj,'clusterHeadSales' => $clusterHeadSalesData,'countOfSalesManager'=>$countOfSalesManager,'clusterHeadFTDSum'=>$clusterHeadFTDSum,'clusterHeadLMTDSum'=>$clusterHeadLMTDSum,'clusterHeadMTDSum'=>$clusterHeadMTDSum]);
        }
    }

    public function getAreaSalesManagerClusterHeadSalesDateReport($id){
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead() || Auth::user()->isStateHead()){

           $areaSalesManagerId = $Vendor->where('parent_id',$id)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = $Vendor->whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $areaSalesManagerSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$areaSalesManagerId)
            ->paginate(100);

            $areaSalesManagerData=$areaSalesManagerSalesObj->getItems();

            $areaSalesManagerFTDSum[] = 0;
            $areaSalesManagerLMTDSum[] = 0;
            $areaSalesManagerMTDSum[] = 0;
            $countOfSalesOfficer[] = 0;
            foreach ($areaSalesManagerData as $areaSalesManager) {
                $area_sales_officer_list=$Vendor->where('type',5)
                ->where('parent_id',$areaSalesManager->id)
                ->lists('user_id');
                $countOfSalesOfficer[$areaSalesManager->id]=sizeof($area_sales_officer_list);

                $sales_executive_list=$Vendor->where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $areaSalesManagerFTDSum[$areaSalesManager->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('aeps_transactions.amount');

                $areaSalesManagerLMTDSum[$areaSalesManager->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('aeps_transactions.amount');

                $areaSalesManagerMTDSum[$areaSalesManager->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('aeps_transactions.amount');

            } 
 
        return View::make('reports.area-sales-manager-cluster-head-date-reports',['areaSalesManagerSalesObj'=>$areaSalesManagerSalesObj,'areaSalesManagerSales' => $areaSalesManagerData,'countOfSalesOfficer'=>$countOfSalesOfficer,'areaSalesManagerFTDSum'=>$areaSalesManagerFTDSum,'areaSalesManagerLMTDSum'=>$areaSalesManagerLMTDSum,'areaSalesManagerMTDSum'=>$areaSalesManagerMTDSum]);
        }
    }

    /*****************************Regional Head Start*****************************/

    public function getRegionalHeadReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead()){

            $stateHeadReportId = $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',10)
            ->lists('user_id');

            $clusterHeadReportId = $Vendor->whereIn('parent_id',$stateHeadReportId)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = $Vendor->whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = $Vendor->whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $stateHeadSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$stateHeadReportId)
            ->paginate(100);

            $stateHeadData=$stateHeadSalesObj->getItems();

            $countOfClusterHead[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($stateHeadData as $stateHead) {

                $cluster_head_id=$Vendor->where('type',7)
                ->where('parent_id',$stateHead->id)
                ->lists('user_id');

                $countOfClusterHead[$stateHead->id]=sizeof($cluster_head_id);

                $areaSalesManager_id=$Vendor->where('type',6)
                ->whereIn('parent_id',$cluster_head_id)
                ->lists('user_id');

                $areaSalesOfficer_id=$Vendor->where('type',5)
                ->whereIn('parent_id',$areaSalesManager_id)
                ->lists('user_id');

                $salesExecutive_id=$Vendor->where('type',4)
                ->whereIn('parent_id',$areaSalesOfficer_id)
                ->lists('user_id');

                $distributor_id=$Vendor->where('type',2)
                ->whereIn('asm_id',$salesExecutive_id)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_id)
                ->lists('user_id');

                $sumOfAgentAmount[$stateHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                    ->whereIn('aeps_transactions.user_id',$agent_list)
                    ->sum('aeps_transactions.amount');

            }

            return View::make('reports.state-head-regional-head-reports',['stateHeadSalesObj'=>$stateHeadSalesObj,'stateHeadSales' => $stateHeadData, 'countOfClusterHead'=>$countOfClusterHead, 'sumOfAgentAmount'=>$sumOfAgentAmount]);
        } 
    }

    public function getClusterHeadForRegionalHeadReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead()){

            $stateHeadReportId= $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',10)
            ->lists('user_id');

            $clusterHeadReportId = $Vendor->whereIn('parent_id',$stateHeadReportId)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = $Vendor->whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = $Vendor->whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $clusterHeadSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$clusterHeadReportId)
            ->paginate(100);

            $clusterHeadData=$clusterHeadSalesObj->getItems();

            $countOfAreaSalesManager[] = 0;
            $sumOfAgentAmount[] = 0;
            foreach ($clusterHeadData as $clusterHead) {
                $area_sales_manager_list=$Vendor->where('type',6)
                ->where('parent_id',$clusterHead->id)
                ->lists('user_id');
                $countOfAreaSalesManager[$clusterHead->id]=sizeof($area_sales_manager_list);

                $area_sales_officer_list=$Vendor->where('type',5)
                ->whereIn('parent_id',$area_sales_manager_list)
                ->lists('user_id');

                $sales_executive_list=$Vendor->where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$clusterHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                    ->whereIn('aeps_transactions.user_id',$agent_list)
                    ->sum('aeps_transactions.amount');


            }
            
            return View::make('reports.cluster-head-reports-for-regional-head',['clusterHeadSalesObj'=>$clusterHeadSalesObj,'clusterHeadSales' => $clusterHeadData, 'countOfAreaSalesManager'=>$countOfAreaSalesManager, 'sumOfAgentAmount'=>$sumOfAgentAmount]);
        } 
    }

    public function getClusterHeadForStateHeadReport($id){
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead()){

            $clusterHeadReportId = $Vendor->where('parent_id',$id)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = $Vendor->whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = $Vendor->whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $clusterHeadSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$clusterHeadReportId)
            ->paginate(100);

            $clusterHeadData=$clusterHeadSalesObj->getItems();

            $countOfAreaSalesManager[] = 0;
            foreach ($clusterHeadData as $clusterHead) {
                $area_sales_manager_list=$Vendor->where('type',6)
                ->where('parent_id',$clusterHead->id)
                ->lists('user_id');
                $countOfAreaSalesManager[$clusterHead->id]=sizeof($area_sales_manager_list);

                $area_sales_officer_list=$Vendor->where('type',5)
                ->whereIn('parent_id',$area_sales_manager_list)
                ->lists('user_id');

                $sales_executive_list=$Vendor->where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $sumOfAgentAmount[$clusterHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                    ->whereIn('aeps_transactions.user_id',$agent_list)
                    ->sum('aeps_transactions.amount');


            }

            return View::make('reports.cluster-head-reports-for-regional-head',['clusterHeadSalesObj'=>$clusterHeadSalesObj,'clusterHeadSales' => $clusterHeadData, 'countOfAreaSalesManager'=>$countOfAreaSalesManager, 'sumOfAgentAmount'=>$sumOfAgentAmount]);
        } 
    }

    public function getStateHeadRegionalHeadSalesDateReport(){
            
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead()){

            $stateHeadReportId= $Vendor->where('parent_id',Auth::user()->id)
            ->where('type',10)
            ->lists('user_id');

            $clusterHeadReportId = $Vendor->whereIn('parent_id',$stateHeadReportId)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = $Vendor->whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = $Vendor->whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $stateHeadSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$stateHeadReportId)
            ->paginate(100);

            $stateHeadSalesData=$stateHeadSalesObj->getItems();
            $stateHeadFTDSum[] = 0;
            $stateHeadLMTDSum[] = 0;
            $stateHeadMTDSum[] = 0;
            $sumOfAgentAmount[] = 0;
            $countOfClusterHead[] = 0;
            $i=0;
            foreach ($stateHeadSalesData as $stateHead) {


                $cluster_head_list=$Vendor->where('type',7)
                ->where('parent_id',$stateHead->id)
                ->lists('user_id');
                $countOfClusterHead[$stateHead->id]=sizeof($cluster_head_list);

                $area_sales_manager_list=$Vendor->where('type',6)
                ->whereIn('parent_id',$cluster_head_list)
                ->lists('user_id');

                $area_sales_officer_list=$Vendor->where('type',5)
                ->whereIn('parent_id',$area_sales_manager_list)
                ->lists('user_id');

                $sales_executive_list=$Vendor->where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $stateHeadFTDSum[$stateHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('aeps_transactions.amount');

                $stateHeadLMTDSum[$stateHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('aeps_transactions.amount');

                $stateHeadMTDSum[$stateHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('aeps_transactions.amount');

                $sumOfAgentAmount[$stateHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                    ->whereIn('aeps_transactions.user_id',$agent_list)
                    ->sum('aeps_transactions.amount');

            } 

        return View::make('reports.state-head-regional-head-date-reports',['stateHeadSalesObj'=>$stateHeadSalesObj,'stateHeadSales' => $stateHeadSalesData,'countOfClusterHead'=>$countOfClusterHead,'stateHeadFTDSum'=>$stateHeadFTDSum,'stateHeadLMTDSum'=>$stateHeadLMTDSum,'stateHeadMTDSum'=>$stateHeadMTDSum]);
        }
    }

    public function getClusterHeadRegionalHeadSalesDateReport($id){
        Paginator::setPageName('page');
        if(Auth::user()->isRegionalHead()){

            

           $clusterHeadReportId = $Vendor->where('parent_id',$id)
            ->where('type',7)
            ->lists('user_id');

            $areaSalesManagerId = $Vendor->whereIn('parent_id',$clusterHeadReportId)
            ->where('type',6)
            ->lists('user_id');

            $areaSalesOfficerId = $Vendor->whereIn('parent_id',$areaSalesManagerId)
            ->where('type',5)
            ->lists('user_id');

            $salesExecutiveuserId = $Vendor->whereIn('parent_id',$areaSalesOfficerId)
            ->where('type',4)
            ->lists('user_id');

            $distributoruserId = $Vendor->whereIn('asm_id',$salesExecutiveuserId)
            ->where('type',2)
            ->lists('user_id');

            $clusterHeadSalesObj = DB::connection('mysql1')->table('user_vendors')
            ->join('users','users.id','=','user_vendors.user_id')
            ->select('users.name','users.phone_no','users.id','user_vendors.csr_id')
            ->whereIn('user_vendors.user_id',$clusterHeadReportId)
            ->paginate(100);

            $clusterHeadSalesData=$clusterHeadSalesObj->getItems();

            //dd($areaSalesOfficerData);
            $clusterHeadFTDSum[] = 0;
            $clusterHeadLMTDSum[] = 0;
            $clusterHeadMTDSum[] = 0;
            $sumOfAgentAmount[] = 0;
            $countOfSalesManager[] = 0;
            foreach ($clusterHeadSalesData as $clusterHead) {
                $area_sales_manager_list=$Vendor->where('type',6)
                ->where('parent_id',$clusterHead->id)
                ->lists('user_id');
                $countOfSalesManager[$clusterHead->id]=sizeof($area_sales_manager_list);

                $area_sales_officer_list=$Vendor->where('type',5)
                ->whereIn('parent_id',$area_sales_manager_list)
                ->lists('user_id');

                $sales_executive_list=$Vendor->where('type',4)
                ->whereIn('parent_id',$area_sales_officer_list)
                ->lists('user_id');

                $distributor_list=$Vendor->where('type',2)
                ->whereIn('asm_id',$sales_executive_list)
                ->lists('user_id');

                $agent_list=$Vendor->where('type',1)
                ->whereIn('parent_id',$distributor_list)
                ->lists('user_id');

                $clusterHeadFTDSum[$clusterHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::yesterday()])
                ->whereRaw('date(created_at) < ?', [Carbon::today()])
                ->sum('aeps_transactions.amount');

                $clusterHeadLMTDSum[$clusterHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::now()->startOfMonth()->subMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::parse('-1 month')])
                ->sum('aeps_transactions.amount');

                $clusterHeadMTDSum[$clusterHead->id]=DB::connection('mysql1')->table('aeps_transactions')
                ->whereIn('aeps_transactions.user_id',$agent_list)
                ->whereRaw('date(created_at) >= ?', [Carbon::today()->startOfMonth()])
                ->whereRaw('date(created_at) <= ?', [Carbon::today()->endOfMonth()])
                ->sum('aeps_transactions.amount');

        }

        return View::make('reports.cluster-head-state-head-date-reports',['clusterHeadSalesObj'=>$clusterHeadSalesObj,'clusterHeadSales' => $clusterHeadSalesData,'countOfSalesManager'=>$countOfSalesManager,'clusterHeadFTDSum'=>$clusterHeadFTDSum,'clusterHeadLMTDSum'=>$clusterHeadLMTDSum,'clusterHeadMTDSum'=>$clusterHeadMTDSum]);
        }
    }



    



}
