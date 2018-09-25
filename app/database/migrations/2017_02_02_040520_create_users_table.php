<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 100);
			$table->string('email', 150)->index();
			$table->string('phone_no', 20);
			$table->string('password', 100);
			$table->tinyInteger('status')->default(0);
			$table->tinyInteger('type')->default(0);
			$table->tinyInteger('email_verified')->default(0);
			$table->tinyInteger('phone_verified')->default(0);
			$table->string('email_token')->nullable()->index();
			$table->string('sms_token')->nullable()->index();
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
		Schema::drop('users');
	}

}
