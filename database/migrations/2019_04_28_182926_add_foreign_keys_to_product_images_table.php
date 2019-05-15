<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProductImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_images', function(Blueprint $table)
		{
			$table->foreign('iae_id', 'IS_ASSIGNED_TO')->references('iae_id')->on('images')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('put_id', 'IS_PICTURED_BY')->references('put_id')->on('products')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_images', function(Blueprint $table)
		{
			$table->dropForeign('IS_ASSIGNED_TO');
			$table->dropForeign('IS_PICTURED_BY');
		});
	}

}
