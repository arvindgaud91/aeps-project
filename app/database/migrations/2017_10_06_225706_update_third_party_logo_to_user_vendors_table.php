<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateThirdPartyLogoToUserVendorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_vendors', function(Blueprint $table)
		{
			$table->string('third_party_logo')->nullable();
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
			$table->dropColumn('third_party_logo');
		});
	}

}
