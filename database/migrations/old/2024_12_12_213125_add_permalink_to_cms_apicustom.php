<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermalinkToCmsApicustom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_apicustom', function (Blueprint $table) {
            $table->string('permalink')->nullable();  // Or the appropriate column type
        });
    }

    public function down()
    {
        Schema::table('cms_apicustom', function (Blueprint $table) {
            $table->dropColumn('permalink');
        });
    }

}
