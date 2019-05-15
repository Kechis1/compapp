<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGuideStepsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('guide_steps', function(Blueprint $table)
		{
			$table->increments('gsp_id');
			$table->integer('gde_id')->unsigned()->index('CONTAINSv3');
			$table->integer('prr_id')->unsigned()->index('SELECTS_VALUE_OF');
			$table->char('gsp_choice', 1);
			$table->boolean('gsp_start');
		});

		DB::statement("ALTER TABLE GUIDE_STEPS ADD CONSTRAINT gsp_choice_check CHECK ( gsp_choice IN ('0', '1', '2') ) ;");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('guide_steps');
	}

}
