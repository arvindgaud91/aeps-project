<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;

/**
*
*/
class PagesController extends HomeController
{
	function __construct()
	{

	}

	public function getMyDistributorsPage ()
	{
		GateKeeper::checkRoles(Auth::user(), 3);
		$Vendor=new Vendor;
       $Vendor->setConnection('mysql1');

       $User=new User;
       $User->setConnection('mysql1');

		$vendorDetails = $Vendor->where('user_id', Auth::user()->id)->first();
		if (! $vendorDetails || $vendorDetails->type != 3) return Redirect::to('/');

		$ids = $Vendor->where('parent_id', Auth::user()->id)
			->where('type', 2)
			->lists('user_id');
		$distributors = $User->whereIn('id', $ids)->with('vendorDetails')->get();
		return View::make('pages.my-distributors')
			->withDistributors($distributors);
	}

	public function getDistributorAgents ($distributorId)
	{
		GateKeeper::checkRoles(Auth::user(), 3);

		if (! Auth::user()) return Redirect::to('/');
		$Vendor=new Vendor;
       $Vendor->setConnection('mysql1');

       $User=new User;
       $User->setConnection('mysql1');
		$vendorDetails = $Vendor->where('user_id', Auth::user()->id)->first();
		if (! $vendorDetails || $vendorDetails->type != 3) return Redirect::to('/');

		//dd(Auth::user()->last_login());

		$distributor = $Vendor->where('user_id', $distributorId)->where('parent_id', Auth::user()->id)->first();
		if (! $distributor) return Redirect::to('/');

		$ids = $Vendor->where('parent_id', $distributorId)
			->where('type', 1)
			->lists('user_id');
		$agents = $User->whereIn('id', $ids)->get();
		return View::make('pages.distributor-agents')
			->withAgents($agents)
			->withDistributor($distributor->user);
	}

	public function getMyAgentsPage ()
	{
		GateKeeper::checkRoles(Auth::user(), 2);
		if (! Auth::user()) return Redirect::to('/');
			$Vendor=new Vendor;
       $Vendor->setConnection('mysql1');

       $User=new User;
       $User->setConnection('mysql1');
		$vendorDetails = $Vendor->where('user_id', Auth::user()->id)->first();
		if (! $vendorDetails || $vendorDetails->type != 2) return Redirect::to('/');

		$ids = $Vendor->where('parent_id', Auth::user()->id)
			->where('type', 1)
			->lists('user_id');
		$agents = $User->whereIn('id', $ids)->with('vendorDetails')->get();
		return View::make('pages.my-agents')
			->withAgents($agents);
	}
	public function update_waller_transactions(){
	    
	    $records = WalletTransaction::get();
	   //dd("fghdfjg");
	    foreach ($records as $record) {

	    	$record_id=WalletAction::where('debit_id',$record->id)->orWhere('credit_id', $record->id)->first();
			//var_dump(json_encode($record_id['credit_id']));
	    	$transaction_id=$record_id['credit_id'] ? $record_id['credit_id']:$record_id['debit_id'];
	    	//echo $transaction_id;
	    	WalletTransaction::where('id', $transaction_id)->update(['user_id' =>$record_id['user_id']]);

	    }
	   
	}   
	public function downloadDriver(){
		return View::make('pages.download-driver');
	} 
	public function customerGuidelines(){
		return View::make('pages.customer-guidelines');
	}  
	public function storeDeviceDetails ($user_id)
	{
		$deviceMake=Input::get('deviceMake');
        $deviceModel=Input::get('deviceModel');
        $deviceSerial=Input::get('deviceSerial');
        
        
        $device_data=array(
            'user_id'=>$user_id,
            'device_make'=>$deviceMake,
            'device_model'=>$deviceModel,
            'device_serial'=>$deviceSerial,
            'created_at'=>date("Y-m-d H:i:s")
        );
        $device = new DeviceDetails();
        $device->insert($device_data);
        return Response::json(['message' => 'Submitted successfully']);
	}
	
}
