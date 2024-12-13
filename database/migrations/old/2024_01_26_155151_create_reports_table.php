<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reports', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->integer('project_id');
			$table->string('path', 50);
			$table->string('token', 100);
			$table->text('options', 65535);
			$table->string('inspector_sign', 300)->nullable();
			$table->dateTime('inspector_sign_at')->nullable();
			$table->string('customer_sign', 300)->nullable();
			$table->dateTime('customer_sign_at')->nullable();
			$table->integer('is_signed')->nullable();
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
		Schema::drop('reports');
	}

}
