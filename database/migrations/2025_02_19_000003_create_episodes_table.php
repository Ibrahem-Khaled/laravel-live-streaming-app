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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            $table->unsignedSmallInteger('season_number')->default(1);
            $table->unsignedSmallInteger('episode_number');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->text('stream_url');
            $table->unsignedInteger('duration')->nullable()->comment('المدة بالثواني');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->date('aired_at')->nullable()->comment('تاريخ العرض');
            $table->boolean('is_active')->default(true)->comment('حالة الحلقة');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['content_id', 'season_number', 'episode_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
