<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE vendors MODIFY status ENUM('pending','approved','rejected','suspended') DEFAULT 'pending'");

        Schema::table('vendors', function (Blueprint $table) {
            $table->string('kyc_business_type')->nullable()->after('shop_description');
            $table->string('kyc_business_registration_number')->nullable()->after('kyc_business_type');
            $table->string('kyc_tax_id')->nullable()->after('kyc_business_registration_number');
            $table->string('kyc_nid_number')->nullable()->after('kyc_tax_id');
            $table->string('kyc_document_path')->nullable()->after('kyc_nid_number');
            $table->enum('kyc_status', ['not_submitted', 'submitted', 'approved', 'rejected'])->default('not_submitted')->after('kyc_document_path');
            $table->text('kyc_rejection_reason')->nullable()->after('kyc_status');
            $table->timestamp('kyc_submitted_at')->nullable()->after('kyc_rejection_reason');
            $table->timestamp('kyc_reviewed_at')->nullable()->after('kyc_submitted_at');
        });
    }

    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn([
                'kyc_business_type',
                'kyc_business_registration_number',
                'kyc_tax_id',
                'kyc_nid_number',
                'kyc_document_path',
                'kyc_status',
                'kyc_rejection_reason',
                'kyc_submitted_at',
                'kyc_reviewed_at',
            ]);
        });

        DB::statement("ALTER TABLE vendors MODIFY status ENUM('pending','approved','suspended') DEFAULT 'pending'");
    }
};
