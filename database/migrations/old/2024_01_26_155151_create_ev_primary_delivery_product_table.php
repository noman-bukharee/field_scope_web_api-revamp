<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEvPrimaryDeliveryProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ev_primary_delivery_product', function(Blueprint $table)
		{
			$table->integer('primary_product_id');
			$table->integer('delivery_product_id');
			$table->timestamps();
			$table->softDeletes();
			$table->unique(['primary_product_id','delivery_product_id'], 'delivery_product_primary_product_mapping');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ev_primary_delivery_product');
	}

}
