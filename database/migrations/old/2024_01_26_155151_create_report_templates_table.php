<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('report_templates', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('company_id');
			$table->string('identifier', 50);
			$table->string('title', 50);
			$table->text('content', 65535)->comment('HTML Content');
			$table->text('path', 65535)->nullable();
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
		Schema::drop('report_templates');
	}

}
