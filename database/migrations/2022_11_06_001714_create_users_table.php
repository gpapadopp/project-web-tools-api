<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id("id");
            $table->string("first_name")->nullable(false);
            $table->string("last_name")->nullable(false);
            $table->string("phone")->nullable(true);
            $table->string("email")->nullable(false)->unique();
            $table->string("username")->nullable(false)->unique();
            $table->string("password")->nullable(false);
            $table->foreignId("role_id")->nullable(false)->references("id")->on("roles");
            $table->boolean("locked")->nullable(false)->default(1);
            $table->boolean("disabled")->nullable(false)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
