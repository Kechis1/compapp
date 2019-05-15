<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeedHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('feed_histories', function(Blueprint $table)
		{
			$table->increments('fhy_id');
			$table->integer('act_id')->unsigned();
			$table->dateTime('fhy_date');
			$table->char('fhy_status', 3);
			$table->string('fhy_message', 200)->nullable();
			$table->unique(['act_id','fhy_date'], 'ffy_act_id_ffy_date_un');
		});

		DB::statement("ALTER TABLE FEED_HISTORIES ADD CONSTRAINT ffy_status_check CHECK (fhy_status IN ('PIG', 'RCD', 'SCS', 'ERR') ) ;");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('feed_histories');
	}

}
