<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('accounts', function(Blueprint $table)
		{
			$table->foreign('act_iae_id', 'HAS_A_LOGO')->references('iae_id')->on('images')->onUpdate('RESTRICT')->onDelete('SET NULL');
			$table->foreign('act_lge_id', 'PREFERS')->references('lge_id')->on('languages')->onUpdate('RESTRICT')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('accounts', function(Blueprint $table)
		{
			$table->dropForeign('HAS_A_LOGO');
			$table->dropForeign('PREFERS');
		});
	}

}
