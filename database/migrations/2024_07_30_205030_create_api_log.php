<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_log', function (Blueprint $table) {
            $table->id();
            $table->string("request_method");
            $table->string("endpoint");
            $table->string("response_status");
            $table->integer("number_of_rows");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_log');
    }
};
