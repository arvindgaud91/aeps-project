<?php

class BalanceRequestLog extends \Eloquent {
	protected $fillable = ['request_id', 'user_id', 'type'];
}