<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGuideStepChoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('guide_step_choices', function(Blueprint $table)
		{
			$table->foreign('gsp_id', 'CONTAINSv4')->references('gsp_id')->on('guide_steps')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('next_step', 'CONTINUES_TO_NEXT')->references('gsp_id')->on('guide_steps')->onUpdate('RESTRICT')->onDelete('SET NULL');
			$table->foreign('iae_id', 'HAS_AN_IMAGE')->references('iae_id')->on('images')->onUpdate('RESTRICT')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('guide_step_choices', function(Blueprint $table)
		{
			$table->dropForeign('CONTAINSv4');
			$table->dropForeign('CONTINUES_TO_NEXT');
			$table->dropForeign('HAS_AN_IMAGE');
		});
	}

}
