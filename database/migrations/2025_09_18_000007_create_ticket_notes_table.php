<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_notes', function (Blueprint $table) {
            $table->id();
            $table->foreign('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreign('author_id')->constrained('users')->cascadeOnDelete();
            $table->enum('note_type', ['Internal', 'External']);
            $table->text('content');
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->timestamp('created_at')->useCurrent();


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_notes');
    }
};


