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
            $table->string('transaction_id');
            $table->tinyInteger('status')->default(1);
            $table->integer('price')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->text('wifi_password')->nullable();

            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
                ->onDelete('cascade');

            // $table->unsignedBigInteger('student_id')->nullable();
            // $table->foreign('student_id')
            //     ->references('id')
            //     ->on('students')
            //     ->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['room_id', 'status']);
            $table->index(['room_id', 'status', 'start_date', 'end_date']);
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
