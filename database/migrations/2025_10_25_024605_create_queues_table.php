<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id(); // Auto-incremented ID for each queue entry
            $table->foreignIdFor(User::class, 'user_id')->constrained()->onDelete('cascade'); // Foreign key to associate with a user
            $table->integer('status_call'); // Integer representing the current status (unavailable, available, etc.)
            $table->timestamp('support_start')->nullable(); // Track when the support session starts
            $table->timestamp('support_end')->nullable(); // Track when the support session ends (optional, can be updated when the user goes back to available)
            $table->timestamps(); // Created_at and updated_at timestamps
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
