<?php

class AepsTransaction extends \Eloquent {
	// protected $fillable = ['user_id', 'aadhar_no', 'amount', 'type', 'bank_id', 'status', 'result', 'result_code', 'balance', 'stan'];
	protected $guarded = ['id'];


	public function user ()
	{
		return $this->belongsTo('User');
	}

	public function commission ()
	{
		return $this->hasOne('TransactionCommission', 'tx_id');
	}

	public function bank ()
	{
		return $this->belongsTo('Bank');
	}


}
