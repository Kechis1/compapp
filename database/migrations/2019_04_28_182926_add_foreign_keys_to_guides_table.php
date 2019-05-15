<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGuidesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('guides', function(Blueprint $table)
		{
			$table->foreign('cey_id', 'IS_ASSIGNED_TOv1')->references('cey_id')->on('categories')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('guides', function(Blueprint $table)
		{
			$table->dropForeign('IS_ASSIGNED_TOv1');
		});
	}

}
