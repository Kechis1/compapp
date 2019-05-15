<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProductCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_categories', function(Blueprint $table)
		{
			$table->foreign('cey_id', 'CONTAINS')->references('cey_id')->on('categories')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('put_id', 'IS_IN_A_CATEGORY')->references('put_id')->on('products')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_categories', function(Blueprint $table)
		{
			$table->dropForeign('CONTAINS');
			$table->dropForeign('IS_IN_A_CATEGORY');
		});
	}

}
