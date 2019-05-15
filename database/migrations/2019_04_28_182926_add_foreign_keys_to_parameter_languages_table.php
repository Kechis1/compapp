<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToParameterLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('parameter_languages', function(Blueprint $table)
		{
			$table->foreign('lge_id', 'BELONGS_TOv3')->references('lge_id')->on('languages')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('prr_id', 'IS_A_VARIETY_OFv4')->references('prr_id')->on('parameters')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('parameter_languages', function(Blueprint $table)
		{
			$table->dropForeign('BELONGS_TOv3');
			$table->dropForeign('IS_A_VARIETY_OFv4');
		});
	}

}
