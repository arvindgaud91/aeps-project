<?php

class WalletAction extends \Eloquent {
	protected $guarded = ['id'];

	public function debit ()
	{
		return $this->belongsTo('WalletTransaction', 'debit_id');
	}

	public function credit ()
	{
		return $this->belongsTo('WalletTransaction', 'credit_id');
	}
}