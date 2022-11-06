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
        Schema::create('courses', function (Blueprint $table) {
            $table->id("id");
            $table->string("name")->nullable(false);
            $table->string("description")->nullable(false);
            $table->foreignId("user_id")->nullable(false)->references("id")->on("users");
            $table->foreignId("course_type_id")->nullable(false)->references("id")->on("course_types");
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
        Schema::dropIfExists('courses');
    }
};
