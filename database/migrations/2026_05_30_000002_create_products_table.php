<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->nullOnDelete();
            $table->foreignId('child_category_id')->nullable()->constrained('child_categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->enum('owner_type', ['admin', 'vendor'])->default('admin');
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->string('thumbnail_path')->nullable();
            $table->json('gallery_paths')->nullable();
            $table->string('video_url')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('offer_price', 12, 2)->nullable();
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->json('sizes')->nullable();
            $table->json('colors')->nullable();
            $table->enum('status', ['draft', 'published', 'inactive'])->default('published');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(true);
            $table->boolean('is_on_sale')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
