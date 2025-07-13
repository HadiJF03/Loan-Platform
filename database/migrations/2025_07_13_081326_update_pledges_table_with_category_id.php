<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pledges', function (Blueprint $table) {
            $table->dropColumn('item_type');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pledges', function (Blueprint $table) {
            $table->enum('item_type', ['Electronics', 'Jewelry', 'Vehicles', 'Real Estate', 'Precious Metals']);
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
