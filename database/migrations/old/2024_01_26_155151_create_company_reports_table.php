<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_reports', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('company_id');
			$table->string('logo_path', 200);
			$table->string('primary_color', 20)->nullable()->comment('company_details');
			$table->string('secondary_color', 20)->nullable()->comment('company_details');
			$table->string('name', 50)->nullable()->comment('company_details');
			$table->string('email', 50)->nullable()->comment('company_details');
			$table->string('phone', 30)->nullable()->comment('company_details');
			$table->string('website', 50)->nullable()->comment('company_details');
			$table->string('services', 200)->nullable()->comment('company_details');
			$table->string('report_name', 50)->nullable();
			$table->string('report_cover_image', 200)->nullable();
			$table->integer('is_footer_user_name')->nullable();
			$table->integer('is_footer_user_email')->nullable();
			$table->integer('is_footer_user_phone')->nullable();
			$table->text('credit_disclaimer', 65535)->nullable();
			$table->boolean('is_disclaimer')->nullable()->comment('credit_disclaimer flag');
			$table->text('estimate_terms', 65535)->nullable()->comment('Owner Authorization');
			$table->text('footer_disclaimer', 65535)->nullable()->comment('Owner Authorization');
			$table->text('json_data', 65535)->nullable();
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
		Schema::drop('company_reports');
	}

}
