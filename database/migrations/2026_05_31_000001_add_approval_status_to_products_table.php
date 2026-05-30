<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                ->default('approved')
                ->after('status');
            $table->text('approval_rejection_reason')->nullable()->after('approval_status');
            $table->timestamp('approved_at')->nullable()->after('approval_rejection_reason');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['approval_status', 'approval_rejection_reason', 'approved_at']);
        });
    }
};
