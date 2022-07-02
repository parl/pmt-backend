<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecurityTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security_tests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('test_case');
            $table->string('priority');
            $table->string('status');
            $table->uuid('project_id');
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects');
        });
        Schema::table('security_tests', function (Blueprint $table) {
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
        Schema::dropIfExists('security_tests');
    }
}
