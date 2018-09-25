<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRenameChildIdToCounterpartIdInDebitRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('debit_requests', function(Blueprint $table)
		{
				$table->renameColumn('child_id', 'counterpart_id');
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
			$table->renameColumn('counterpart_id', 'child_id');
		});
	}

}
