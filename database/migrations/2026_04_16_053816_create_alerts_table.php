<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Supports:
     *  - Admin-posted broadcast alerts  (type = 'admin')
     *  - Go-bag expiry/restock alerts   (type = 'expiry')
     */
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['admin', 'expiry'])->default('admin');
            // For expiry alerts — links back to the personal go-bag item that triggered it
            $table->foreignId('checklist_item_id')->nullable()->constrained('go_bag_items')->nullOnDelete();
            // For expiry alerts — links back to the family go-bag item that triggered it
            $table->foreignId('checklist_item_family_id')->nullable()->constrained('go_bag_items_family')->nullOnDelete();
            // For expiry alerts scoped to a specific user; NULL = broadcast to all
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        // Per-user read tracking (avoids a nullable boolean on a broadcast row)
        Schema::create('alert_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('read_at')->useCurrent();
            $table->unique(['alert_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_reads');
        Schema::dropIfExists('alerts');
    }
};