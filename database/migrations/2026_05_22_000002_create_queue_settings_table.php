<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queue_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(true);
            $table->boolean('auto_enqueue_on_payment')->default(true);
            $table->boolean('show_queue_on_receipt')->default(true);
            $table->boolean('reset_queue_daily')->default(true);
            $table->unsignedSmallInteger('estimated_minutes')->default(15);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_settings');
    }
};
