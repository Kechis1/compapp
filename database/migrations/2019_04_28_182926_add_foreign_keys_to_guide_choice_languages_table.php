<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGuideChoiceLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('guide_choice_languages', function(Blueprint $table)
		{
			$table->foreign('lge_id', 'BELONGS_TOv6')->references('lge_id')->on('languages')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('gse_id', 'IS_A_VARIETY_OFv6')->references('gse_id')->on('guide_step_choices')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('guide_choice_languages', function(Blueprint $table)
		{
			$table->dropForeign('BELONGS_TOv6');
			$table->dropForeign('IS_A_VARIETY_OFv6');
		});
	}

}
