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
        Schema::create('evaluations_metas', function (Blueprint $table) {
            $table->id("id");
            $table->foreignId("evaluation_id")->nullable(false)->references("id")->on("evaluations");
            $table->string("meta_key")->nullable(false);
            $table->float("meta_value")->nullable(false);
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
        Schema::dropIfExists('evaluations_metas');
    }
};
