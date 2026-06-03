<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'courier_tracking_id')) {
                $table->string('courier_tracking_id')->nullable()->after('transaction_id');
            }

            if (! Schema::hasColumn('orders', 'shipping_status')) {
                $table->string('shipping_status')->default('not_sent')->after('courier_tracking_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_status')) {
                $table->dropColumn('shipping_status');
            }

            if (Schema::hasColumn('orders', 'courier_tracking_id')) {
                $table->dropColumn('courier_tracking_id');
            }
        });
    }
};
