<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReviewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reviews', function(Blueprint $table)
		{
			$table->increments('rvw_id');
			$table->integer('act_id')->unsigned();
			$table->string('rvw_title', 50);
			$table->text('rvw_message', 65535);
			$table->decimal('rvw_rating', 1, 0)->unsigned();
			$table->dateTime('rvw_date_created');
			$table->integer('put_id')->unsigned()->nullable();
			$table->integer('lge_id')->unsigned()->nullable();
			$table->integer('ete_act_id')->unsigned()->nullable()->index('HAS_A_REVIEW');
			$table->text('rvw_pros', 65535)->nullable();
			$table->text('rvw_cons', 65535)->nullable();
			$table->unique(['act_id','put_id'], 'rvw_act_id_put_id_un');
			$table->unique(['act_id','ete_act_id'], 'rvw_act_id_ete_act_id_un');
			$table->index(['put_id','lge_id'], 'HAS_A_REVIEWv1');
		});

        DB::statement("ALTER TABLE REVIEWS ADD CONSTRAINT rvw_rating CHECK (rvw_rating BETWEEN 1 AND 5);");
        DB::statement("ALTER TABLE REVIEWS ADD CONSTRAINT rvw_ete CHECK ((put_id IS NULL AND ete_act_id IS NOT NULL) OR (put_id IS NOT NULL AND ete_act_id IS NULL));");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reviews');
	}

}
