<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoryParametersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('category_parameters', function(Blueprint $table)
		{
			$table->integer('prr_id')->unsigned();
			$table->integer('cey_id')->unsigned()->index('CONTAINSv1');
			$table->primary(['prr_id','cey_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('category_parameters');
	}

}
