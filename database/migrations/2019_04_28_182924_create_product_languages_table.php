<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_languages', function(Blueprint $table)
		{
			$table->integer('put_id')->unsigned();
			$table->integer('lge_id')->unsigned()->index('BELONGS_TO');
			$table->string('ple_url', 150);
			$table->string('ple_name', 125);
			$table->boolean('ple_active');
			$table->text('ple_desc_short', 65535)->nullable();
			$table->text('ple_desc_long', 65535)->nullable();
			$table->primary(['put_id','lge_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_languages');
	}

}
