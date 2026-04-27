<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('quantity')->default(1);

            // STL file block
            $table->string('stl_file_path')->nullable();
            $table->decimal('stl_volume_mm3', 10, 4)->nullable();
            $table->decimal('stl_dim_x', 10, 4)->nullable();
            $table->decimal('stl_dim_y', 10, 4)->nullable();
            $table->decimal('stl_dim_z', 10, 4)->nullable();

            // this serves as a snapshot for estimated prices
            $table->decimal('estimated_price', 8, 2)->nullable();

            $table->enum('status', ['pending', 'quoted', 'rejected'])->default('pending');
            $table->timestamps();

            $table->index('user_id');
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
