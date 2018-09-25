<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Add230620161624ColumnsToAepsTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('aeps_transactions', function(Blueprint $table)
		{
			$table->string('request_id')->index();
			$table->string('uidai_auth_code');
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

		});
	}

}
