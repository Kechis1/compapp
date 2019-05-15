<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToParameterValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('parameter_values', function(Blueprint $table)
		{
			$table->foreign('prr_id', 'BELONGS_TOv4')->references('prr_id')->on('parameters')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('parameter_values', function(Blueprint $table)
		{
			$table->dropForeign('BELONGS_TOv4');
		});
	}

}
