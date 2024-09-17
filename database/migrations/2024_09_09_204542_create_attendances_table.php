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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('weekend_id')->nullable();
            $table->unsignedBigInteger('holiday_id')->nullable();
            $table->string('status');
            $table->time('check_in');
            $table->time('check_out');
            $table->date('date');
            $table->decimal('hours', 5, 2);
    

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('weekend_id')->references('id')->on('weekends')->onDelete('set null');
            $table->foreign('holiday_id')->references('id')->on('holidays')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
