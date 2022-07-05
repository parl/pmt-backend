<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('test_case');
            $table->string('priority');
            $table->string('status');
            $table->string('description');
            $table->string('components');
            $table->string('steps_to_reproduce');
            $table->string('result');
            $table->string('expected_result');
            $table->string('attachment');
            $table->string('type');
            $table->uuid('project_id');
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects');
        });
        Schema::table('tests', function (Blueprint $table) {
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
        Schema::dropIfExists('tests');
    }
}
