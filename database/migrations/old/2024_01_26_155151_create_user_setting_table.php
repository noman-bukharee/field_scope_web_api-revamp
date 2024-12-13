<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserSettingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_setting', function(Blueprint $table)
		{
			$table->integer('setting_id')->unsigned();
			$table->integer('tenant_id');
			$table->string('value', 150);
			$table->timestamps();
			$table->primary(['setting_id','tenant_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_setting');
	}

}
