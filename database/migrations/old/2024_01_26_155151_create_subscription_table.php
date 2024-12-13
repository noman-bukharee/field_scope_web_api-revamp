<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscriptionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscription', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('type', 50)->nullable();
			$table->string('key', 145);
			$table->string('title', 45)->nullable();
			$table->decimal('amount', 9)->nullable();
			$table->decimal('per_user_amount', 9)->nullable()->default(0.00);
			$table->integer('min_user')->default(1)->comment('min. user charge limit');
			$table->text('description', 65535)->nullable();
			$table->string('duration', 45)->nullable();
			$table->string('duration_unit', 45)->nullable();
			$table->integer('total_tiers')->nullable();
			$table->integer('total_featured_deals')->nullable();
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
		Schema::drop('subscription');
	}

}
