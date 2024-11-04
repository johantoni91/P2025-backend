<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layout', function (Blueprint $table) {
            $table->id();
            $table->text('app_name');
            $table->text('short_app_name');
            $table->enum('header', [0, 1])->default('1');
            $table->enum('footer', [0, 1])->default('1');
            $table->enum('fullscreen', [0, 1])->default('1');
            $table->text('icon')->nullable();
            $table->text('img_login_bg')->nullable();
            $table->enum('login_position', ['left', 'center', 'right'])->default('left');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layout');
    }
};
