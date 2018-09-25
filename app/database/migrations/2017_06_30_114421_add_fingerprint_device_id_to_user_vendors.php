<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddFingerprintDeviceIdToUserVendors extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_vendors', function(Blueprint $table)
		{
			$table->integer('fingerprint_device_id')->unsigned();
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
			$table->dropColumn('fingerprint_device_id');
		});
	}

}
