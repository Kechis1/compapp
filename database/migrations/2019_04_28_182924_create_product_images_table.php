<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_images', function(Blueprint $table)
		{
			$table->integer('iae_id')->unsigned();
			$table->integer('put_id')->unsigned()->index('IS_PICTURED_BY');
			$table->boolean('pie_active');
			$table->primary(['iae_id','put_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_images');
	}

}
