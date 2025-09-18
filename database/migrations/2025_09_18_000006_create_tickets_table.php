<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('ticket_id');
            $table->unsignedBigInteger('requester_id');
            $table->unsignedBigInteger('assigned_staff_id')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->enum('urgency_level', ['Low', 'Medium', 'High']);
            $table->enum('status', ['Pending', 'In Progress', 'Resolved', 'Closed']);
            $table->text('issue_description');
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('requester_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_staff_id')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('category_id')->references('category_id')->on('categories');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};


