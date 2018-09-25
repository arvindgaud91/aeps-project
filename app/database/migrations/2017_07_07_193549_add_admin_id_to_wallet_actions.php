<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAdminIdToWalletActions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('wallet_actions', function(Blueprint $table)
		{
			$table->integer('admin_id')->unsigned()->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('wallet_actions', function(Blueprint $table)
		{
			$table->dropColumn('admin_id');
		});
	}

}
