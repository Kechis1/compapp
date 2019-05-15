<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductParametersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_parameters', function(Blueprint $table)
		{
			$table->integer('put_id')->unsigned();
			$table->integer('pve_id')->unsigned()->index('HAS_A_VALUE');
			$table->boolean('ppr_active');
			$table->primary(['put_id','pve_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_parameters');
	}

}
