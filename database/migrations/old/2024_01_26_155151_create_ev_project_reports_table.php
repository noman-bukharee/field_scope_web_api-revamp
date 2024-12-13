<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEvProjectReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ev_project_reports', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('project_id')->nullable();
			$table->integer('report_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('status_id')->nullable()->comment('1:Created | 2:InProcess | 3:Pending | 4:Closed | 5:Completed');
			$table->timestamps();
			$table->string('request_params')->nullable();
			$table->unique(['project_id','report_id'], 'project_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ev_project_reports');
	}

}
