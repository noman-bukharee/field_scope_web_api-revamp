<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Adding default roles
        DB::table('roles')->insert([
            ['name' => 'Admin'],
            ['name' => 'Manager'],
            ['name' => 'Standard'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
