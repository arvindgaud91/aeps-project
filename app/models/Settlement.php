<?php

class Settlement extends \Eloquent {
	protected $fillable = ['user_id', 'beneficiary_name', 'account_number', 'ifsc_code','settlement_type','bank_name','created_at','settlement_bank_id'];
	protected $table='settlement_bank_account';
}
