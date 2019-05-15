<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFeedHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('feed_histories', function(Blueprint $table)
		{
			$table->foreign('act_id', 'HAS_HISTORY')->references('act_id')->on('accounts')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('feed_histories', function(Blueprint $table)
		{
			$table->dropForeign('HAS_HISTORY');
		});
	}

}
