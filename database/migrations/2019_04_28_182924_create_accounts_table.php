<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accounts', function(Blueprint $table)
		{
			$table->increments('act_id');
			$table->integer('act_lge_id')->unsigned()->index('PREFERS');
			$table->string('act_email', 100)->unique('act_act_email_un');
			$table->char('act_type', 3);
			$table->dateTime('act_date_created');
			$table->integer('act_iae_id')->unsigned()->nullable()->index('HAS_A_LOGO');
			$table->char('amr_password', 60)->nullable();
			$table->boolean('amr_active')->nullable();
			$table->string('amr_code_refresh', 64)->nullable();
			$table->string('amr_first_name', 30)->nullable();
			$table->string('amr_last_name', 40)->nullable();
			$table->string('ete_tin', 15)->nullable();
			$table->string('ete_vatin', 25)->nullable();
			$table->string('ete_name', 100)->nullable();
			$table->string('ete_cellnumber', 20)->nullable();
			$table->string('ete_url_feed', 200)->nullable();
			$table->string('ete_url_web', 100)->nullable();
			$table->string('ete_country', 60)->nullable();
			$table->string('ete_street', 60)->nullable();
			$table->string('ete_zip', 12)->nullable();
			$table->string('ete_city', 60)->nullable();
			$table->rememberToken();
		});

		// Add check constraint
        DB::statement("ALTER TABLE ACCOUNTS ADD CONSTRAINT act_check CHECK (act_type = 'UER' OR (act_type = 'ETE' AND (amr_password IS NOT NULL AND amr_active IS NOT NULL AND amr_first_name IS NOT NULL AND amr_last_name IS NOT NULL AND ete_tin IS NOT NULL AND ete_name IS NOT NULL AND ete_cellnumber IS NOT NULL AND ete_url_feed IS NOT NULL AND ete_url_web IS NOT NULL AND ete_country IS NOT NULL AND ete_street IS NOT NULL AND ete_zip IS NOT NULL AND ete_city IS NOT NULL )) OR (act_type = 'AMR' AND ( amr_password IS NOT NULL AND amr_active IS NOT NULL AND amr_first_name IS NOT NULL AND amr_last_name IS NOT NULL )));");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('accounts');
	}

}
