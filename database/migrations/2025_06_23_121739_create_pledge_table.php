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
        Schema::create('pledges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('item_type',['Electronics','Jewelry','Vehicles','Real Estate','Precious Metals']);
            $table->text('description');
            $table->json('images')->nullable();
            $table->decimal('requested_amount',10 , 2);
            $table->integer('collateral_duration');
            $table->string('repayment_terms');
            $table->enum('status', ['open','negotiating','finalized','withdrawn'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pledges');
    }
};
