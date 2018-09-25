<?php

class SessionResetOTP extends \Eloquent {
	protected $fillable = ['user_id', 'otp', 'ip'];
	protected $table = 'session_reset_otp_tokens';

	/**
	* Relationships of this model
	*
	*/
	public function user ()
	{
		return $this->belongsTo('User');
	}

	/**
	* Helper functions to deal with this model
	*
	*/
	public static function getOTP ($token, $userId)
	{
		$sessionToken = self::where('otp', $token)
			->where('user_id', $userId)
      // ->where('status', 0)
      ->orderBy('created_at', 'DESC')
      ->first();
		if (! $sessionToken) return false;
		$timeDiff = ((new DateTime)->getTimestamp() - $sessionToken->created_at->getTimestamp());
    if ($timeDiff > 50*60) {
			return false;
		}
		return $sessionToken;
	}
}