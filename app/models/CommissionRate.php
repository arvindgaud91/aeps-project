<?php

class CommissionRate extends \Eloquent {
	protected $fillable = ['user_id', 'master_id', 'rate', 'balance_enquiry_rate', 'rate_type'];

	/**
	 * Relationships
	 *
	 */
	public function user ()
	{
		return $this->belongsTo('User');
	}
	public function master ()
	{
		return $this->belongsTo('CommissionMaster');
	}
}