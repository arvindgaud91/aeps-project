<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAadhaarCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('aadhaar_codes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('response_code', 20);
			$table->string('uidai_error_code', 20);
			$table->string('description', 100);
			$table->boolean('receipt_required');
			$table->string('status', 10);
			$table->integer('status_code');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('aadhaar_codes');
	}

}
