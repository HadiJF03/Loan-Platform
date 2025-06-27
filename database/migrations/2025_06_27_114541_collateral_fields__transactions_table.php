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
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('collateral_confirmed_by_pledger')->default(false);
            $table->boolean('collateral_confirmed_by_pledgee')->default(false);
            $table->boolean('payment_confirmed_by_pledger')->default(false);
            $table->boolean('payment_confirmed_by_pledgee')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('collateral_confirmed_by_pledger');
            $table->dropColumn('collateral_confirmed_by_pledgee');
            $table->dropColumn('payment_confirmed_by_pledger');
            $table->dropColumn('payment_confirmed_by_pledgee');
        });
    }
};
