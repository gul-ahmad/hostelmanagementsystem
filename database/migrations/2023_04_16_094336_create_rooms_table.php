<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id');
            $table->tinyInteger('room_number');
            $table->tinyInteger('room_floor_number');
            $table->tinyInteger('approval_status')->default(1); //may be room is not approved by admin for reservation due to any faults
            $table->boolean('hidden')->default(false);
            $table->integer('capactiy')->default(3); //total students per room 
            $table->boolean('full_occupation_allow')->default(true); //one student can book whole room
            $table->integer('price_per_month');
            $table->integer('discount_on_full_occupation_per_month')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('offices_tags', function (Blueprint $table) {

            $table->foreignId('office_id')->index();
            $table->foreignId('tag_id')->index();
            $table->unique(['office_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};
