<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddActivityAndNarrationToWalletTransactions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('wallet_transactions', function(Blueprint $table)
		{
			$table->string('activity', 100);
			$table->string('narration', 200);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('wallet_transactions', function(Blueprint $table)
		{
			$table->dropColumn('activity', 'narration');
		});
	}

}
