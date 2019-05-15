<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGuideStepLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('guide_step_languages', function(Blueprint $table)
		{
			$table->integer('gsp_id')->unsigned();
			$table->integer('lge_id')->unsigned()->index('BELONGS_TOv5');
			$table->string('gss_title', 100);
			$table->text('gss_description', 65535)->nullable();
			$table->primary(['gsp_id','lge_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('guide_step_languages');
	}

}
