<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToChoiceValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('choice_values', function(Blueprint $table)
		{
			$table->foreign('pve_id', 'BELONGS_TOv7')->references('pve_id')->on('parameter_values')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('gse_id', 'HAS_A_VALUEv3')->references('gse_id')->on('guide_step_choices')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('choice_values', function(Blueprint $table)
		{
			$table->dropForeign('BELONGS_TOv7');
			$table->dropForeign('HAS_A_VALUEv3');
		});
	}

}
