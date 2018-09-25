<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token', 'sms_token', 'email_token'];

	protected $fillable = ['name', 'email', 'phone_no', 'password'];

	/**
	* Relationships of this model
	*
	*/
	public function vendor ()
	{
		return $this->hasOne('Vendor');
	}
	public function vendorBankAccount ()
	{
		return $this->hasOne('VendorBankAccount');
	}
	public function vendorDetails ()
	{
		return $this->hasOne('Vendor');
	}
	public function permissions ()
	{
		return $this->hasMany('ServicePermission');
	}
	public function blacklist ()
	{
		return $this->hasMany('Blacklist');
	}
	public function last_login ()
	{
		return $this->hasMany('LastLogin');
	}

	/**
	* Helper functions
	*
	*/
	public function isRegionalHead ()
	{
		$vendor = Vendor::where('user_id', $this->id)
			->where('type', 11)
			->first();
		return (! $vendor) ? false : true;
	}
	public function isStateHead ()
	{
		$vendor = Vendor::where('user_id', $this->id)
			->where('type', 10)
			->first();
		return (! $vendor) ? false : true;
	}
	public function isClusterHead ()
	{
		$vendor = Vendor::where('user_id', $this->id)
			->where('type', 7)
			->first();
		return (! $vendor) ? false : true;
	}
	public function isAreaSalesManager ()
	{
		$vendor = Vendor::where('user_id', $this->id)
			->where('type', 6)
			->first();
		return (! $vendor) ? false : true;
	}
	public function isAreaSalesOfficer ()
	{
		$vendor = Vendor::where('user_id', $this->id)
			->where('type', 5)
			->first();
		return (! $vendor) ? false : true;
	}
	public function isSalesExecutive ()
	{
		$vendor = Vendor::where('user_id', $this->id)
			->where('type', 4)
			->first();
		return (! $vendor) ? false : true;
	}
	public function isSuperDistributor ()
	{
		$vendor = Vendor::where('user_id', $this->id)
			->where('type', 3)
			->first();
		return (! $vendor) ? false : true;
	}
	public function isDistributor ()
	{
		$vendor = Vendor::where('user_id', $this->id)
			->where('type', 2)
			->first();
		return (! $vendor) ? false : true;
	}
	public function isAgent ()
	{
		$vendor = Vendor::where('user_id', $this->id)
			->where('type', 1)
			->first();
		return (! $vendor) ? false : true;
	}
	public function getBlacklist ()
	{
		return array_map(function ($item)
		{
			return $item['permission'];
		}, $this->blacklist->toArray());
	}


}
