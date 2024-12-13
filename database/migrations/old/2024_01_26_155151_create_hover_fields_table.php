<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHoverFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hover_fields', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 50);
			$table->integer('hover_type_id');
			$table->string('config_path', 100);
			$table->string('params', 100)->nullable()->comment('comma_separated');
			$table->string('method', 100)->nullable();
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
		Schema::drop('hover_fields');
	}

}
