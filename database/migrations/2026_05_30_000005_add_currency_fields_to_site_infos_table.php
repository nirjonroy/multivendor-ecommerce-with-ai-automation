<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('site_infos', function (Blueprint $table) {
            $table->string('currency_code', 10)->default('USD')->after('short_description');
            $table->string('currency_symbol', 10)->default('$')->after('currency_code');
            $table->enum('currency_position', ['left', 'right'])->default('left')->after('currency_symbol');
            $table->decimal('currency_rate', 12, 4)->default(1)->after('currency_position');
        });
    }

    public function down()
    {
        Schema::table('site_infos', function (Blueprint $table) {
            $table->dropColumn(['currency_code', 'currency_symbol', 'currency_position', 'currency_rate']);
        });
    }
};
