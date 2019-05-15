<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('categories', function(Blueprint $table)
		{
			$table->foreign('cey_cey_id', 'HAS_A_PARENT')->references('cey_id')->on('categories')->onUpdate('RESTRICT')->onDelete('SET NULL');
			$table->foreign('iae_id', 'IS_PICTURED_BYv1')->references('iae_id')->on('images')->onUpdate('RESTRICT')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('categories', function(Blueprint $table)
		{
			$table->dropForeign('HAS_A_PARENT');
			$table->dropForeign('IS_PICTURED_BYv1');
		});
	}

}
