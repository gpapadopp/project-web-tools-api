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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id("id");
            $table->string("token")->nullable(false)->unique();
            $table->boolean("is_done")->nullable(false)->default(0);
            $table->float("average")->nullable(true);
            $table->foreignId("course_id")->nullable(false)->references("id")->on("courses");
            $table->foreignId("user_id")->nullable(false)->references("id")->on("users");
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
        Schema::dropIfExists('evaluations');
    }
};
