<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pre_ticket_communications', function (Blueprint $table) {
            $table->id('communication_id');
            $table->unsignedBigInteger('requester_id');
            $table->unsignedBigInteger('mis_staff_id');
            $table->text('issue_description');
            $table->text('suggested_solution')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('requester_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('mis_staff_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_ticket_communications');
    }
};


