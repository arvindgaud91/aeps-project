<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRateTypeToCommissionRatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('commission_rates', function(Blueprint $table)
		{
				$table->tinyInteger('rate_type');
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
			$table->dropColumn('rate_type');
		});
	}

}
