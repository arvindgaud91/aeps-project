<?php

class DebitRequest extends \Eloquent {
	protected $fillable = ['user_id', 'child_id', 'amount', 'remarks'];
}