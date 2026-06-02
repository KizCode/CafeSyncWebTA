<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('queue_number', 32)->nullable()->after('status');
            $table->foreignId('production_status_id')->nullable()->after('queue_number')->constrained('production_statuses')->nullOnDelete();
            $table->timestamp('queued_at')->nullable()->after('production_status_id');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['production_status_id']);
            $table->dropColumn(['queue_number', 'production_status_id', 'queued_at']);
        });
    }
};
