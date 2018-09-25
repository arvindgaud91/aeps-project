<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserVendorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_vendors', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->tinyInteger('type');
			$table->integer('city');
			$table->integer('state');
			$table->tinyInteger('zone');
			$table->string('pan_no', 10);
			$table->integer('parent_id')->default(0);
			$table->string('bank_agent_id', 11)->nullable();
			$table->string('device_id', 9)->nullable();
			$table->string('device_sr_no', 16)->nullable();
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
		Schema::drop('user_vendors');
	}

}
