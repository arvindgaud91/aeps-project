<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddBalanceToAepsTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('aeps_transactions', function(Blueprint $table)
		{
			$table->double('balance');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('aeps_transactions', function(Blueprint $table)
		{
			$table->dropColumn('balance');
		});
	}

}
