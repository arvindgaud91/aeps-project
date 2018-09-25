<?php

class AepsToDmt extends \Eloquent {
	protected $fillable = ['user_id', 'status', 'transaction_amount','transaction_id', 'remaining_amount','created_at'];
	protected $table='aeps_to_dmt_request';
}
