<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductEnterprisesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_enterprises', function(Blueprint $table)
		{
			$table->increments('pee_id');
			$table->integer('put_id')->unsigned()->index('IS_OFFERED');
			$table->integer('act_id')->unsigned();
			$table->string('pee_url', 250);
			$table->float('pee_price', 10, 0)->unsigned();
			$table->decimal('pee_availability', 2, 0);
			$table->boolean('pee_active');
			$table->unique(['pee_url','put_id'], 'pee_pee_url_act_id_un');
			$table->unique(['act_id','put_id'], 'pee_put_id_act_id_un');
		});

        DB::statement("ALTER TABLE PRODUCT_ENTERPRISES ADD CONSTRAINT pee_availability_check CHECK (pee_availability BETWEEN -1 AND 30) ;");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_enterprises');
	}

}
