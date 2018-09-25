<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRenameBalanceRequestVendorsToWalletBalanceVendorRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
			Schema::rename('balance_request_vendors', 'wallet_balance_vendor_requests');
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
			Schema::rename('wallet_balance_vendor_requests', 'balance_request_vendors');
	}

}
