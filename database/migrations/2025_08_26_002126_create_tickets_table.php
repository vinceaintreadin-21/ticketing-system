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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['bug', 'new_request', 'fix', 'enhancement', 'inquiry']);
            $table->enum('category',['developer', 'technicians'])->nullable();
            $table->enum('status', ['pending', 'ongoing', 'closed', 'resolved', 'cancelled'])->default('open');
            $table->enum('urgency', ['low', 'medium', 'high'])->nullable();

            $table->integer('priority')->default(0);
            $table->date('expected_completion_date')->nullable();

            $table->foreignId('department_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('requester_id')->nullable()->constrained('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
