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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('cnic');
            $table->string('contact_no');
            $table->string('emergency_contact_number');
            $table->enum('gender', ['male', 'female']);
            $table->text('address');
            $table->string('university/college');
            $table->string('nationality');
            $table->string('passport_number')->nullable();

            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
                ->onDelete('cascade');

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
        Schema::dropIfExists('students');
    }
};
