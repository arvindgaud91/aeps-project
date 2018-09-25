<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddMasterIdToCommissionRates extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('commission_rates', function(Blueprint $table)
		{
			$table->dropColumn('min');
			$table->dropColumn('max');
			$table->integer('master_id')->unsigned();
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
			$table->integer('min');
			$table->integer('max');
			$table->dropColumn('master_id');
		});
	}

}
