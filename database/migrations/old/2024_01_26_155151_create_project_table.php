<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('project', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned();
			$table->string('name', 100);
			$table->string('address1', 100);
			$table->string('address2', 100)->nullable();
			$table->integer('assigned_user_id')->unsigned()->default(0);
			$table->integer('state_id')->unsigned()->nullable();
			$table->integer('city_id')->unsigned()->nullable();
			$table->string('postal_code', 100)->nullable();
			$table->string('claim_num', 100)->nullable();
			$table->float('sales_tax', 10, 0)->nullable();
			$table->date('inspection_date')->nullable();
			$table->string('latitude', 100);
			$table->string('longitude', 100);
			$table->string('customer_email', 50)->nullable();
			$table->integer('is_updated')->default(0);
			$table->timestamps();
			$table->softDeletes();
			$table->integer('user_id')->default(0);
			$table->integer('status_id')->nullable()->default(1)->comment('0:unsynced,1:synced');
			$table->integer('project_status')->nullable()->default(1)->comment('1:open, 2:closed');
			$table->string('ref_id', 100)->nullable()->comment('app local id');
			$table->string('crm_project_id', 100)->nullable();
			$table->string('map_thumbnail', 200)->nullable();
			$table->dateTime('last_crm_sync_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('project');
	}

}
