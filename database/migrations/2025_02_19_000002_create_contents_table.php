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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable()->comment('لوجو/بوستر');
            $table->string('poster')->nullable()->comment('بوستر كبير (أفلام/مسلسلات)');
            $table->string('banner')->nullable()->comment('بانر عرض');
            $table->enum('type', ['channel', 'movie', 'series', 'live'])->default('channel');
            $table->text('stream_url')->nullable()->comment('رابط البث الرئيسي');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedSmallInteger('year')->nullable()->comment('سنة الإنتاج');
            $table->unsignedInteger('duration')->nullable()->comment('المدة بالدقائق (أفلام)');
            $table->decimal('rating', 3, 1)->nullable()->comment('تقييم 0-10');
            $table->string('quality')->nullable()->comment('HD, FHD, 4K');
            $table->string('language')->nullable()->comment('لغة المحتوى');
            $table->string('country')->nullable()->comment('دولة الإنتاج');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('extra')->nullable()->comment('حقول إضافية مرنة');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
