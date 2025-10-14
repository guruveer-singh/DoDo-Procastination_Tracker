<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('reminders', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('title');
        $table->text('description')->nullable();
        $table->dateTime('reminder_time')->nullable();
        $table->timestamps();

        // Link to users table
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

};
