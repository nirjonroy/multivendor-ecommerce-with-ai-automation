<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('billing_address')->nullable()->after('phone');
            $table->text('shipping_address')->nullable()->after('billing_address');
            $table->boolean('newsletter_subscribed')->default(false)->after('shipping_address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['billing_address', 'shipping_address', 'newsletter_subscribed']);
        });
    }
};
