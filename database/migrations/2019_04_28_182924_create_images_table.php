<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('images', function(Blueprint $table)
		{
			$table->increments('iae_id');
			$table->string('iae_path', 90)->unique('iae_iae_path_un');
			$table->string('iae_name', 90);
			$table->float('iae_size', 10, 0);
			$table->string('iae_type', 4);
		});

        DB::statement("ALTER TABLE IMAGES ADD CONSTRAINT iae_type_check CHECK (iae_type IN ('jpg', 'jpeg', 'png', 'gif')) ;");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('images');
	}

}
