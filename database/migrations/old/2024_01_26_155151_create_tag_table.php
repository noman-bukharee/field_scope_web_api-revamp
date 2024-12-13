<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tag', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned();
			$table->integer('ref_id')->nullable()->comment('2nd level cat');
			$table->string('ref_type', 10)->nullable();
			$table->string('name', 100);
			$table->string('annotation', 100);
			$table->integer('has_qty')->unsigned();
			$table->boolean('is_required')->default(0);
			$table->float('price', 10, 0)->nullable();
			$table->integer('uom_id')->nullable();
			$table->float('material_cost', 10, 0)->nullable();
			$table->float('labor_cost', 10, 0)->nullable();
			$table->float('equipment_cost', 10, 0)->nullable();
			$table->float('supervision_cost', 10, 0)->nullable();
			$table->float('margin', 10, 0)->nullable();
			$table->string('spec_type')->nullable();
			$table->string('build_spec')->nullable();
			$table->integer('hover_field_type_id');
			$table->integer('hover_field_id');
			$table->integer('ev_primary_product_id')->nullable();
			$table->integer('ev_product_field_id')->nullable();
			$table->integer('order_by')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('target_id')->nullable()->default(0)->comment('For photoview id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tag');
	}

}
