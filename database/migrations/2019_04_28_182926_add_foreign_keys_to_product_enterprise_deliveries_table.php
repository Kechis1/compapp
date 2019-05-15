<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProductEnterpriseDeliveriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_enterprise_deliveries', function(Blueprint $table)
		{
			$table->foreign('pee_id', 'HAS_A_DELIVERY')->references('pee_id')->on('product_enterprises')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('dly_id', 'IS_DELIVERED_BY')->references('dly_id')->on('deliveries')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_enterprise_deliveries', function(Blueprint $table)
		{
			$table->dropForeign('HAS_A_DELIVERY');
			$table->dropForeign('IS_DELIVERED_BY');
		});
	}

}
