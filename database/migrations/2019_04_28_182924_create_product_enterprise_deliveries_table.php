<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductEnterpriseDeliveriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_enterprise_deliveries', function(Blueprint $table)
		{
			$table->integer('dly_id')->unsigned();
			$table->integer('pee_id')->unsigned()->index('HAS_A_DELIVERY');
			$table->float('pey_price', 10, 0)->unsigned();
			$table->float('pey_price_cod', 10, 0)->unsigned();
			$table->primary(['dly_id','pee_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_enterprise_deliveries');
	}

}
