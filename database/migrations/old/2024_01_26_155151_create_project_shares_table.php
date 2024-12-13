<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectSharesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('project_shares', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('company_id');
			$table->integer('project_id');
			$table->string('share_token', 200);
			$table->string('recipient_email', 100);
			$table->integer('status')->default(1);
			$table->integer('creator_id');
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
		Schema::drop('project_shares');
	}

}
