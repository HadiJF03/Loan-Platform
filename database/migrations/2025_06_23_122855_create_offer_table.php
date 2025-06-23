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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pledge_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('offer_amount', 10, 2);
            $table->integer('duration');
            $table->text('terms')->nullable();
            $table->enum('status', ['pending', 'amended', 'accepted', 'rejected'])->default('pending');
            $table->boolean('is_amendment')->default(false);
            $table->foreignId('parent_offer_id')->nullable()->constrained('offers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
