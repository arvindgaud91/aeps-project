<?php

class RefundOTP extends \Eloquent {
	protected $fillable = ['transaction_id', 'otp', 'ip'];
	protected $table = 'refund_otp_tokens';

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
	public static function getOTP ($token, $transaction_id)
	{
		$refundToken = self::where('otp', $token)
			->where('transaction_id', $transaction_id)
       ->where('status', 1)
      ->orderBy('created_at', 'DESC')
      ->first();
		if (! $refundToken) return false;
		$refundToken->status=2;
		$refundToken->save();
		$timeDiff = ((new DateTime)->getTimestamp() - $refundToken->created_at->getTimestamp());
    if ($timeDiff > 50*60) {
    	
			return false;
		}
		return $refundToken;
	}
}