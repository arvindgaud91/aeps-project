<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Remove300620171215ColumnsFromUserVendors extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_vendors', function(Blueprint $table)
		{
			$table->dropColumn('terminal_id');
			$table->dropColumn('csr_password');
			$table->dropColumn('freshness_factor');
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
			$table->string('terminal_id', 30);
			$table->string('csr_password');
			$table->string('freshness_factor');
		});
	}

}
