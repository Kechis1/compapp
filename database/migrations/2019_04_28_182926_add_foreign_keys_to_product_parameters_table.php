<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProductParametersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_parameters', function(Blueprint $table)
		{
			$table->foreign('put_id', 'DEFINES')->references('put_id')->on('products')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('pve_id', 'HAS_A_VALUE')->references('pve_id')->on('parameter_values')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_parameters', function(Blueprint $table)
		{
			$table->dropForeign('DEFINES');
			$table->dropForeign('HAS_A_VALUE');
		});
	}

}
