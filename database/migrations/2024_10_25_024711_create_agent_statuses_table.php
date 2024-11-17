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
        Schema::create('agent_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId(\http\Client\Curl\User::class);
            $table->foreignIdFor(\App\Models\Status::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_statuses');
    }
};
