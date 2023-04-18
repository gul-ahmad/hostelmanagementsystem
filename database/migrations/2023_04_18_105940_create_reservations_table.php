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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            //$table->integer('price');
            $table->tinyInteger('approval_status')->default(2);
            $table->date('start_date');
            $table->date('end_date');
            $table->text('wifi_password')->nullable();

            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
                ->onDelete('cascade');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');

            $table->timestamps();

            $table->index(['student_id', 'approval_status']);
            $table->index(['room_id', 'approval_status']);
            $table->index(['room_id', 'approval_status', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
