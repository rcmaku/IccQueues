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
        Schema::create('case_handlings', function (Blueprint $table) {
            $table->id();
            $table->foreignId(\http\Client\Curl\User::class);
            $table->string('interpreter_Name');
            $table->timestamps();
            $table->dateTime('resolutionDate');
            $table->string('caseAccepted');
            $table->string('declineReason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_handlings');
    }
};
