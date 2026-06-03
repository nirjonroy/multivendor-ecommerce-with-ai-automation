<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('site_infos', function (Blueprint $table) {
            $table->boolean('newsletter_popup_enabled')->default(true)->after('currency_rate');
            $table->string('newsletter_popup_title')->default('Newsletter')->after('newsletter_popup_enabled');
            $table->text('newsletter_popup_description')->nullable()->after('newsletter_popup_title');
            $table->string('newsletter_popup_button_text', 50)->default('Subscribe')->after('newsletter_popup_description');
            $table->string('newsletter_popup_image_path')->nullable()->after('newsletter_popup_button_text');
        });
    }

    public function down()
    {
        Schema::table('site_infos', function (Blueprint $table) {
            $table->dropColumn([
                'newsletter_popup_enabled',
                'newsletter_popup_title',
                'newsletter_popup_description',
                'newsletter_popup_button_text',
                'newsletter_popup_image_path',
            ]);
        });
    }
};
