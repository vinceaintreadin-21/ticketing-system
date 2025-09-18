<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_history', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('performed_by');
            $table->string('action');
            $table->text('details');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('ticket_id')->references('ticket_id')->on('tickets')->onDelete('cascade');
            $table->foreign('performed_by')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_history');
    }
};


