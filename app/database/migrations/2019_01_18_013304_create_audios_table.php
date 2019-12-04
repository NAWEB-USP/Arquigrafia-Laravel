<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudiosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audios', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('file');
			$table->text('name')->nullable();
			$table->text('description')->nullable();
			$table->integer('user_id');
			$table->integer('photo_id');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('audios');
	}

}
