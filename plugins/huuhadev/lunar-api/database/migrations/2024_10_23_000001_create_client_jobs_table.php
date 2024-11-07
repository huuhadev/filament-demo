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
        Schema::create('lunar_api_client_jobs', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->timestamps();
            $table->string('api');
            $table->string('resource_type');
            $table->string('resource_id')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('attempts')->default(0);
            $table->integer('timeout')->nullable();
            $table->timestamp('timeout_at')->nullable();
            $table->integer('tries')->nullable();
            $table->boolean('failed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lunar_api_client_jobs');
    }
};
