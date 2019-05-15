<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProductEnterprisesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_enterprises', function(Blueprint $table)
		{
			$table->foreign('put_id', 'IS_OFFERED')->references('put_id')->on('products')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('act_id', 'OFFERS')->references('act_id')->on('accounts')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_enterprises', function(Blueprint $table)
		{
			$table->dropForeign('IS_OFFERED');
			$table->dropForeign('OFFERS');
		});
	}

}
