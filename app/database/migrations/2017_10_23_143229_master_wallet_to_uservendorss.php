<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MasterWalletToUservendorss extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_vendors', function(Blueprint $table)
		{
			$table->integer('master_wallet_id')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_vendors', function(Blueprint $table)
		{
			$table->dropColumn('master_wallet_id');
		});
	}

}
