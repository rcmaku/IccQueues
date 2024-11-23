<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use App\Models\User;
use \App\Models\Role;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agent_roles', function (Blueprint $table) {
            $table->id(); // auto-increment primary key
            $table->foreignIdFor(User::class, 'user_id')->constrained()->onDelete('cascade'); // Foreign key for user
            $table->foreignIdFor(Role::class, 'role_id')->constrained()->onDelete('cascade'); // Foreign key for rolemng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_roles');
    }
};
