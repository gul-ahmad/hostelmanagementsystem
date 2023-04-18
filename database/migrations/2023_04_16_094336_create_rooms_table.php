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
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('rooms_tags', function (Blueprint $table) {

            $table->foreignId('room_id')->index();
            $table->foreignId('tag_id')->index();
            $table->unique(['room_id', 'tag_id']);
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
