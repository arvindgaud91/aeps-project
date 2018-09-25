<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseBalanceActionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_balance_actions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->integer('counterpart_id')->unsigned()->index();
			$table->double('amount');
			$table->string('remarks', 255);
			$table->tinyInteger('status');
			$table->integer('debit_id')->unsigned();
			$table->integer('credit_id')->unsigned();
			$table->tinyInteger('type');
			$table->boolean('admin')->default(false);
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
		Schema::drop('purchase_balance_actions');
	}

}
