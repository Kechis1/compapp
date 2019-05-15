<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGuideStepsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('guide_steps', function(Blueprint $table)
		{
			$table->foreign('gde_id', 'CONTAINSv3')->references('gde_id')->on('guides')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('prr_id', 'SELECTS_VALUE_OF')->references('prr_id')->on('parameters')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('guide_steps', function(Blueprint $table)
		{
			$table->dropForeign('CONTAINSv3');
			$table->dropForeign('SELECTS_VALUE_OF');
		});
	}

}
