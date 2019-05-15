<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToReviewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('reviews', function(Blueprint $table)
		{
			$table->foreign('act_id', 'CREATED')->references('act_id')->on('accounts')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('ete_act_id', 'HAS_A_REVIEW')->references('act_id')->on('accounts')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('put_id', 'HAS_A_REVIEWv1')->references('put_id')->on('product_languages')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('reviews', function(Blueprint $table)
		{
			$table->dropForeign('CREATED');
			$table->dropForeign('HAS_A_REVIEW');
			$table->dropForeign('HAS_A_REVIEWv1');
		});
	}

}
