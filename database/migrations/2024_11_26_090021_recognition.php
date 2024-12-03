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
        Schema::create('recognition', function (Blueprint $table) {
            $table->id();
            $table->enum('mask', ['with_mask', 'without_mask', 'mask_worn_incorrectly', 'mask_worn_correctly'])->default('without_mask');
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->float('similarity')->default(0.97);
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
        Schema::dropIfExists('recognition');
    }
};
