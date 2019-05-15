<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGuidesLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('guides_languages', function(Blueprint $table)
		{
			$table->foreign('lge_id', 'BELONGS_TOv1')->references('lge_id')->on('languages')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('gde_id', 'IS_A_VARIETY_OFv2')->references('gde_id')->on('guides')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('guides_languages', function(Blueprint $table)
		{
			$table->dropForeign('BELONGS_TOv1');
			$table->dropForeign('IS_A_VARIETY_OFv2');
		});
	}

}
