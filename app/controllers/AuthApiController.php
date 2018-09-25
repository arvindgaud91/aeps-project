<?php

use Acme\Auth\Auth;

class AuthApiController extends BaseController {

	public function postSetUser ()
	{
		if (! Input::has('token')) return Response::json(['message' => "Missing important information."], 422);
		$user = Auth::validateToken(Input::get('token')); 
		$user->permissions = ServicePermission::where('user_id', $user->id)->lists('permission');
		return Response::json($user, 200);
	}


	public function getUserParent ($id)
	{
		if (! Request::header('auth')) return Response::json(['message' => 'Missing auth token'], 422);
		$token = Request::header('auth');
		$user = Auth::validateToken($token);
		if (! $user) return Resposne::json(['message' => 'User not available or active.']);
		
		$parent = User::with('vendor')->find($id);
		return Response::json($parent, 200); 
	}

	public function getCities ()
	{
		if (! Request::header('auth')) return Response::json(['message' => 'Missing auth token'], 422);
		$token = Request::header('auth');
		$user = Auth::validateToken($token);
		if (! $user) return Resposne::json(['message' => 'User not available or active.']);
		return Response::json(City::get(), 200);	
	}

	public function getBanks ()
	{
		if (! Request::header('auth')) return Response::json(['message' => 'Missing auth token'], 422);
		$token = Request::header('auth');
		$user = Auth::validateToken($token);
		if (! $user) return Resposne::json(['message' => 'User not available or active.']);
		return Response::json(Bank::get(), 200);	
	}

	public function getUsers ()
	{
		if (! Request::header('auth')) return Response::json(['message' => 'Missing auth token'], 422);
		$token = Request::header('auth');
		$user = Auth::validateToken($token);
		if (! $user) return Resposne::json(['message' => 'User not available or active.']);
		if (! Input::has('user_ids')) return Response::json(['message' => 'Missing important information.'], 422);
 		$userIds = Input::get('user_ids');
		$users = User::whereIn('id', $userIds)->with('vendor')->get();
		return Response::json($users, 200);
	}

	public function getChildren ()
	{
		if (! Request::header('auth')) return Response::json(['message' => 'Missing auth token'], 422);
		$token = Request::header('auth');
		$user = Auth::validateToken($token);
		if (! $user) return Resposne::json(['message' => 'User not available or active.']);
		$childrenIds = Vendor::where('parent_id', $user->id)->lists('user_id');
		$children = User::whereIn('id', $childrenIds)->with('vendor')->with('permissions')->get();
		$children = array_filter(json_decode($children), function ($child)
		{
			$flag = false;
			foreach ($child->permissions as $permission) {
				$flag = $permission->permission == 'dmt' ? true : $flag;
			}
			return $flag;
		});
		return Response::json($children, 200);

	}
	public function getChild ($id)
	{
		if (! Request::header('auth')) return Response::json(['message' => 'Missing auth token'], 422);
		$token = Request::header('auth');
		$user = Auth::validateToken($token);
		if (! $user) return Resposne::json(['message' => 'User not available or active.']);
		$child = User::where('id', $id)->first();
		
		return Response::json($child, 200);

	}
	
	public function postVerifyAdmin ()
	{
		if (! Input::has('token')) return Response::json(['message' => 'Missing important info.']);
		$admin_token = Input::get('token');
		$admin = Auth::validateToken($admin_token);
		if (! $admin) return Response::json(false, 422);
		if ($admin->type == 8 || $admin->type == 9) 
			return Response::json($admin->id, 200);
	}

	public function getDmtChildren ()
	{
		if (! Request::header('auth')) return Response::json(['message' => 'Missing auth token'], 422);
		$token = Request::header('auth');
		$user = Auth::validateToken($token);
		if (! $user) return Resposne::json(['message' => 'User not available or active.']);
		//$childrenIds = Vendor::where('parent_id', $user->id)->lists('user_id');
			 $childrenIds  = Input::get('agentsId');//$this->filterOnly(Input::all(), ['agentsId']);
		$children = User::whereIn('id', $childrenIds)->with('vendor')->with('permissions')->get();
		/*$children = array_filter(json_decode($children), function ($child)
		{
			$flag = false;
			foreach ($child->permissions as $permission) {
				$flag = $permission->permission == 'dmt' ? true : $flag;
			}
			return $flag;
		});*/
		return Response::json($children, 200);

	}

	public function getUserDetails ()
	{
		if (! Request::header('auth')) return Response::json(['message' => 'Missing auth token'], 422);
		$token = Request::header('auth');
		$user = Auth::validateToken($token);
		if (! $user) return Resposne::json(['message' => 'User not available or active.']);
		$message = array("0" => array ("message" => 'Missing important information.'));
		if (! Input::has('user_ids')) return Response::json($message, 422);
 		$userIds = Input::get('user_ids');
		$users = User::whereIn('id', $userIds)
		->select('users.name','users.id','users.phone_no')
		->get();
		return Response::json($users, 200);
	}

	public function getUsersDetailsForAdmin ()
	{
		if (! Input::has('user_ids')) return Response::json(['message' => 'Missing important information.'], 422);
 		$userIds = Input::get('user_ids');
		$users = User::whereIn('id', $userIds)->with('vendor')->get();
		return Response::json($users, 200);
	}
}

?>
