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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pledge_id')->constrained()->onDelete('cascade');
            $table->foreignId('offer_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('collateral_status', ['active', 'closed', 'delayed', 'lost'])->default('active');
            $table->enum('payment_status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->decimal('commission', 10, 2)->default(0);
            $table->enum('payment_method',['Card Payment','Bank Transfer', 'STC Pay']);
            $table->enum('delivery_method',['in-person','shipping','secure drop point']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
