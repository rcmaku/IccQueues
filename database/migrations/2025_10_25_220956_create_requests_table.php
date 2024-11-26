<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title'); // Title of the request
            $table->string('channel'); // Submission channel (e.g., web, email)
            $table->timestamp('start_time')->nullable(); // When the work on the request started
            $table->timestamp('end_time')->nullable(); // When the work on the request ended
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID of the user who worked on the request
            $table->timestamps(); // Laravel's created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
