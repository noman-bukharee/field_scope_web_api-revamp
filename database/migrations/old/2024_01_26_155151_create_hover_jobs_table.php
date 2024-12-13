<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHoverJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hover_jobs', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('company_id');
			$table->string('project_ref_id', 50)->nullable()->comment('app local key');
			$table->integer('project_id');
			$table->integer('job_id');
			$table->integer('deliverable_id');
			$table->string('state', 100)->nullable();
			$table->text('json_response', 65535);
			$table->timestamps();
			$table->softDeletes();
			$table->string('file_path', 200);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hover_jobs');
	}

}
