<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracesTable extends Migration
{
    public function up()
    {
        Schema::create('traces', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('path');
            $table->decimal('total_time', 20, 10);
            $table->unsignedBigInteger('total_memory');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('traces');
    }
}