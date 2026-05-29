<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('size_prices')->nullable()->after('colors');
            $table->json('color_images')->nullable()->after('size_prices');
            $table->json('variation_stocks')->nullable()->after('color_images');
            $table->boolean('has_variation_stock')->default(false)->after('variation_stocks');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['size_prices', 'color_images', 'variation_stocks', 'has_variation_stock']);
        });
    }
};
