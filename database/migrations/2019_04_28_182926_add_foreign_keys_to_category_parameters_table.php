<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCategoryParametersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('category_parameters', function(Blueprint $table)
		{
			$table->foreign('cey_id', 'CONTAINSv1')->references('cey_id')->on('categories')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('prr_id', 'IS_IN_A_CATEGORYv1')->references('prr_id')->on('parameters')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('category_parameters', function(Blueprint $table)
		{
			$table->dropForeign('CONTAINSv1');
			$table->dropForeign('IS_IN_A_CATEGORYv1');
		});
	}

}
