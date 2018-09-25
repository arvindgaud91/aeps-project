<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Add030620171506ColumnsToDebitRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('debit_requests', function(Blueprint $table)
		{
      $table->integer('wallet_request_id')->unsigned()->nullable();
      $table->tinyInteger('type');
      $table->boolean('admin')->default(false);
      $table->boolean('automatic')->default(false);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('debit_requests', function(Blueprint $table)
		{
      $table->dropColumn('wallet_request_id')->unsigned()->index();
      $table->dropColumn('type');
      $table->dropColumn('admin')->default(false);
      $table->dropColumn('automatic')->default(false);
		});
	}

}
