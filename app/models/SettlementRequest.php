<?php

class SettlementRequest extends \Eloquent {

	protected $fillable = ['user_id', 'status', 'transaction_amount','transaction_id', 'remaining_amount','account_number','created_at','settlement_bank_id'];

	protected $table='settlement_request';
}
