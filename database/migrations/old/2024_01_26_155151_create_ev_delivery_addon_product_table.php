<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEvDeliveryAddonProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ev_delivery_addon_product', function(Blueprint $table)
		{
			$table->integer('delivery_product_id');
			$table->integer('addon_id');
			$table->timestamps();
			$table->softDeletes();
			$table->unique(['delivery_product_id','addon_id'], 'dp_ao_mapping');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ev_delivery_addon_product');
	}

}
