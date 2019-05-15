<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoryLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('category_languages', function(Blueprint $table)
		{
			$table->integer('cey_id')->unsigned();
			$table->integer('lge_id')->unsigned()->index('BELONGS_TOv2');
			$table->string('cle_url', 250);
			$table->string('cle_name', 70);
			$table->boolean('cle_active');
			$table->text('cle_description', 65535)->nullable();
			$table->primary(['cey_id','lge_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('category_languages');
	}

}
