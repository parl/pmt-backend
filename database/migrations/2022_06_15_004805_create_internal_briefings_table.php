<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalBriefingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_briefings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('task');
            $table->string('category');
            $table->uuid('project_id');
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects');
        });
        Schema::table('internal_briefings', function (Blueprint $table) {
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
        Schema::dropIfExists('internal_briefings');
    }
}
