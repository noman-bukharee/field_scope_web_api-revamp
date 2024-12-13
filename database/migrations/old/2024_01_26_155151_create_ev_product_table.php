<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEvProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ev_product', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 50);
			$table->text('description', 65535)->nullable();
			$table->integer('parent_id')->default(0);
			$table->string('type', 50)->comment('primary_product, delivery_product, addon_product');
			$table->text('detailed_description', 65535)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->text('json', 65535);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ev_product');
	}

}
