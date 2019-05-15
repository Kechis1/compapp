<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGuidesLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('guides_languages', function(Blueprint $table)
		{
			$table->integer('gde_id')->unsigned();
			$table->integer('lge_id')->unsigned()->index('BELONGS_TOv1');
			$table->string('gle_name', 70);
			$table->boolean('gle_active');
			$table->primary(['gde_id','lge_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('guides_languages');
	}

}
