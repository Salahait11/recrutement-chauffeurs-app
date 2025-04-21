php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('interviews', function (Blueprint $table) {
           $table->id();
           $table->unsignedBigInteger('candidate_id');
           $table->unsignedBigInteger('scheduler_id');
           $table->dateTime('interview_date');
           $table->string('type');
           $table->text('notes')->nullable();
           $table->string('status');
           $table->string('result')->nullable();
           $table->text('feedback')->nullable();
           $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->foreign('scheduler_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};