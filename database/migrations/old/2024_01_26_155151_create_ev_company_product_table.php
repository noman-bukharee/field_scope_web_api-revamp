<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEvCompanyProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ev_company_product', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('company_id');
			$table->integer('ev_primary_product_id');
			$table->integer('ev_delivery_product_id')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ev_company_product');
	}

}
