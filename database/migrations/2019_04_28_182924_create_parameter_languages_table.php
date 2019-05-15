<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateParameterLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('parameter_languages', function(Blueprint $table)
		{
			$table->integer('prr_id')->unsigned()->index('IS_A_VARIETY_OFv4');
			$table->integer('lge_id')->unsigned();
			$table->string('pls_name', 80);
			$table->string('pls_unit', 10)->nullable();
			$table->primary(['lge_id','prr_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('parameter_languages');
	}

}
