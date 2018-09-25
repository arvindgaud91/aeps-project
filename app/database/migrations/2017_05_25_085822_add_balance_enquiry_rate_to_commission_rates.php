<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddBalanceEnquiryRateToCommissionRates extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('commission_rates', function(Blueprint $table)
		{
			$table->double('balance_enquiry_rate');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('commission_rates', function(Blueprint $table)
		{
			$table->dropColumn('balance_enquiry_rate');
		});
	}

}
