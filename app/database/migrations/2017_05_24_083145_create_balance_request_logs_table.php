<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBalanceRequestLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('balance_request_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('request_id')->unsigned()->index();
			$table->integer('user_id')->unsigned()->index();
			$table->tinyInteger('type');
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
		Schema::drop('balance_request_logs');
	}

}
