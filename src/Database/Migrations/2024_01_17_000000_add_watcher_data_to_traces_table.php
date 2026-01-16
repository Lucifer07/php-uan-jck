<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('traces', function (Blueprint $table) {
            $table->integer('query_count')->default(0)->after('total_memory');
            $table->json('slow_queries')->nullable()->after('query_count');
            $table->json('request_data')->nullable()->after('slow_queries');
        });
    }

    public function down()
    {
        Schema::table('traces', function (Blueprint $table) {
            $table->dropColumn(['query_count', 'slow_queries', 'request_data']);
        });
    }
};
