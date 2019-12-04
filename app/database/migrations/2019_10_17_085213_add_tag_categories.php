<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTagCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tags', function($table) {
			$table->dropColumn('category');
			$table->string('cat_1');
			$table->string('cat_2');
			$table->string('cat_3');
			$table->string('cat_4');
			$table->string('cat_5');
			$table->string('cat_6');
			$table->string('cat_7');
			$table->string('cat_8');
			$table->string('cat_9');
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
    		$table->string('category');
			$table->dropColumn('cat_1');
			$table->dropColumn('cat_2');
			$table->dropColumn('cat_3');
			$table->dropColumn('cat_4');
			$table->dropColumn('cat_5');
			$table->dropColumn('cat_6');
			$table->dropColumn('cat_7');
			$table->dropColumn('cat_8');
			$table->dropColumn('cat_9');
		});
	}

}
