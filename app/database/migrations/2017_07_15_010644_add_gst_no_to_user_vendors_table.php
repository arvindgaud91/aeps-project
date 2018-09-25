<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddGstNoToUserVendorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_vendors', function(Blueprint $table)
		{
			$table->string('gst_no', 50);
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
			$table->dropColumn('gst_no');
		});
	}

}
