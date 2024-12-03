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
        Schema::create('access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('roles_id');
            $table->unsignedBigInteger('modules_id');

            $table->enum('status', ['0', '1'])->default(1);
            $table->enum('dashboard', ['0', '1'])->default(1);
            $table->enum('graph', ['0', '1'])->default(1);
            $table->enum('face', ['0', '1'])->default(1);
            $table->enum('create', ['0', '1'])->default(1);
            $table->enum('update', ['0', '1'])->default(1);
            $table->enum('delete', ['0', '1'])->default(1);

            $table->foreign('roles_id')->references('id')->on('roles');
            $table->foreign('modules_id')->references('id')->on('modules');
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
        Schema::dropIfExists('access');
    }
};
