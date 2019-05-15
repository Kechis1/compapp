<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToParameterValueLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('parameter_value_languages', function(Blueprint $table)
		{
			$table->foreign('pve_id', 'IS_A_VARIETY_OFv5')->references('pve_id')->on('parameter_values')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('lge_id', 'IS_MEANT_ONLY_FOR')->references('lge_id')->on('languages')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('parameter_value_languages', function(Blueprint $table)
		{
			$table->dropForeign('IS_A_VARIETY_OFv5');
			$table->dropForeign('IS_MEANT_ONLY_FOR');
		});
	}

}
