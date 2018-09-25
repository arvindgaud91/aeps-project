<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;
//use App\Models\Support;

/**
*
*/
class SupportController extends HomeController
{
	function __construct()
	{
            
                 
	}

	public function add_support ()
	{
		$ticket_id=Input::get('ticket_id');
        $type=Input::get('type');
        $message=Input::get('message');
        
         $validator = Validator::make(array(
            'ticket_id'=>$ticket_id,
            'type'=>$type,
            'message'=>$message,
            'status'=>'Open'
        ), array(
            'ticket_id' => 'required',
            'type' => 'required',
            'message' => 'required'
            
          ));
          if ($validator->fails())
            return Response::json($validator->messages(), 500);
           $support_data=array(
            'ticket_id'=>$ticket_id,
            'type'=>$type,
            'user_id'=>Auth::user()->id,
            'message'=>$message,
            'status'=>'Open',
            'created_at'=>date("Y-m-d H:i:s")
        );
         $support = new Support();
         $support->insert($support_data);
          //return $balanceRequest;
	}
  public function support_report ()
	{
          
  	Paginator::setPageName('page');
  	$data = Support::join('master_support', 'support.type', '=', 'master_support.support_id') ->select('support.*', 'master_support.support_name')->orderBy('id', 'desc')->where('user_id',Auth::user()->id)->paginate(10);
          
          return View::make('support.support_report', ['support_data' => $data]);
           
  }
        
	

	
}
