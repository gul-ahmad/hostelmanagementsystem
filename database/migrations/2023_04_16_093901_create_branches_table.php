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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->index();
            $table->string('title');
            $table->text('description');
            $table->text('address_line1');
            $table->text('address_line2')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->boolean('hidden')->default(false);
            $table->tinyInteger('total_rooms');
            $table->tinyInteger('floors');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
};
