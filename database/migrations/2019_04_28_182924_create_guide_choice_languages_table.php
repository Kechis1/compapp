<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGuideChoiceLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('guide_choice_languages', function(Blueprint $table)
		{
			$table->integer('gse_id')->unsigned();
			$table->integer('lge_id')->unsigned()->index('BELONGS_TOv6');
			$table->text('gce_pros', 65535)->nullable();
			$table->text('gce_cons', 65535)->nullable();
			$table->string('gce_title', 50);
			$table->text('gce_description', 65535)->nullable();
			$table->primary(['gse_id','lge_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('guide_choice_languages');
	}

}
