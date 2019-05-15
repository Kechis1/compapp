<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateParameterValueLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('parameter_value_languages', function(Blueprint $table)
		{
			$table->integer('pve_id')->unsigned();
			$table->integer('lge_id')->unsigned()->index('IS_MEANT_ONLY_FOR');
			$table->string('pvs_value', 50);
			$table->boolean('pvs_active');
			$table->primary(['pve_id','lge_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('parameter_value_languages');
	}

}
