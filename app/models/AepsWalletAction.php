<?php

class AepsWalletAction extends \Eloquent {
	protected $guarded = ['id'];

	public function debit ()
	{
		return $this->belongsTo('WalletTransaction', 'debit_id');
	}

	public function credit ()
	{
		return $this->belongsTo('WalletTransaction', 'credit_id');
	}

	public function transaction ()
	{
		return $this->belongsTo('AepsTransaction', 'transaction_id');
	}
}
