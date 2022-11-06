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
        Schema::create('roles', function (Blueprint $table) {
            $table->id("id");
            $table->string("name")->nullable(false);
            $table->string("description")->nullable(false);
            $table->boolean("create_right")->nullable(false)->default(0);
            $table->boolean("read_right")->nullable(false)->default(0);
            $table->boolean("update_right")->nullable(false)->default(0);
            $table->boolean("delete_right")->nullable(false)->default(0);
            $table->boolean("super_admin")->nullable(false)->default(0);
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
        Schema::dropIfExists('roles');
    }
};
