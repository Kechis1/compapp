<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProductLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_languages', function(Blueprint $table)
		{
			$table->foreign('lge_id', 'BELONGS_TO')->references('lge_id')->on('languages')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('put_id', 'IS_A_VARIETY_OF')->references('put_id')->on('products')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_languages', function(Blueprint $table)
		{
			$table->dropForeign('BELONGS_TO');
			$table->dropForeign('IS_A_VARIETY_OF');
		});
	}

}
