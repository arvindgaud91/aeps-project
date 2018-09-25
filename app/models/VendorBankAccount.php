<?php

class VendorBankAccount extends \Eloquent {
	protected $fillable = ['user_id', 'bank_id', 'acc_type', 'acc_no', 'ifsc_code'];
}

?>