<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRrnToAepsTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('aeps_transactions', function(Blueprint $table)
		{
			$table->string('rrn', 20);
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
			$table->dropColumn('rrn');
		});
	}

}
