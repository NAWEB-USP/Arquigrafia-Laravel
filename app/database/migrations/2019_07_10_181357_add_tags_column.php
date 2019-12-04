<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTagsColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tags', function ($table) {
            $table->string('description')->nullable();
            $table->string('category')->nullable();
            $table->integer('is');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tags', function ($table) {
    		$table->dropColumn('description');
            $table->dropColumn('category');
            $table->dropColumn('is');
		});
	}

}
