<?php

class LastLogin extends \Eloquent {
	protected $fillable = ['user_id', 'ip'];

	/**
	* Relationships of this model
	*
	*/
	public function user ()
	{
		return $this->belongsTo('User');
	}
}