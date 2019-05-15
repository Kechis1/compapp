<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCategoryLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('category_languages', function(Blueprint $table)
		{
			$table->foreign('lge_id', 'BELONGS_TOv2')->references('lge_id')->on('languages')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('cey_id', 'IS_A_VARIETY_OFv3')->references('cey_id')->on('categories')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('category_languages', function(Blueprint $table)
		{
			$table->dropForeign('BELONGS_TOv2');
			$table->dropForeign('IS_A_VARIETY_OFv3');
		});
	}

}
