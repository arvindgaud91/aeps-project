<?php

class BankLoginLog extends \Eloquent {
	protected $fillable = ['user_id', 'request', 'response'];
}
