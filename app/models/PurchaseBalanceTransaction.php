<?php

class PurchaseBalanceTransaction extends \Eloquent {
	protected $fillable = ['user_id', 'amount', 'transaction_type', 'balance', 'message'];
}