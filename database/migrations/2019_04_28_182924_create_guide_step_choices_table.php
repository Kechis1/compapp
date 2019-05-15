<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGuideStepChoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('guide_step_choices', function(Blueprint $table)
		{
			$table->increments('gse_id');
			$table->integer('gsp_id')->unsigned()->index('CONTAINSv4');
			$table->boolean('gse_default');
			$table->integer('next_step')->unsigned()->nullable()->index('CONTINUES_TO_NEXT');
			$table->integer('iae_id')->unsigned()->nullable()->index('HAS_AN_IMAGE');
			$table->float('gse_min', 10, 0)->nullable();
			$table->float('gse_max', 10, 0)->nullable();
		});

        DB::statement("ALTER TABLE GUIDE_STEP_CHOICES ADD CONSTRAINT gse_min_max_check CHECK (gse_min <= gse_max) ;");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('guide_step_choices');
	}

}
