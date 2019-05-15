<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChoiceValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('choice_values', function(Blueprint $table)
		{
			$table->integer('gse_id')->unsigned();
			$table->integer('pve_id')->unsigned()->index('BELONGS_TOv7');
			$table->primary(['gse_id','pve_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('choice_values');
	}

}
