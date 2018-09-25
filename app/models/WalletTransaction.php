<?php

class WalletTransaction extends \Eloquent {

	protected $fillable = ['user_id', 'transaction_type', 'amount', 'balance', 'activity', 'narration'];
}
