<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGuideStepLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('guide_step_languages', function(Blueprint $table)
		{
			$table->foreign('lge_id', 'BELONGS_TOv5')->references('lge_id')->on('languages')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('gsp_id', 'IS_A_VARIETY_OFv1')->references('gsp_id')->on('guide_steps')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('guide_step_languages', function(Blueprint $table)
		{
			$table->dropForeign('BELONGS_TOv5');
			$table->dropForeign('IS_A_VARIETY_OFv1');
		});
	}

}
