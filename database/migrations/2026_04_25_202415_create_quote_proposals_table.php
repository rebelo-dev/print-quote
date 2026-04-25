<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quote_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('estimated_hours', 8, 2)->nullable();
            $table->decimal('estimated_weight', 8, 2)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price', 8, 2);
            $table->text('notes')->nullable();

            // these 2 next fields are for future proofing in case i want to automate the client process
            $table->string('accept_token')->unique()->nullable();
            $table->timestamp('accepted_at')->nullable();

            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected'])->default('draft');
            $table->timestamps();

            $table->index('user_id');
            $table->index('quote_request_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_proposals');
    }
};
