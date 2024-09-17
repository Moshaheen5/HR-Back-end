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
        Schema::create('salary_actions', function (Blueprint $table) {
            $table->id();  
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('attendance_id');
<<<<<<< HEAD:database/migrations/2024_09_12_124408_create_salary_actions_table.php

=======
>>>>>>> a14bc99f5aa678380a1961ebb5afcf80831a7cc3:database/migrations/2024_09_09_204539_create_salary_actions_table.php
            $table->date('date');  
            $table->enum('type', ['bonus', 'deduction']);  
            $table->decimal('amount', 10, 2); 
            $table->decimal('hours', 5, 2)->nullable();  
            $table->text('details')->nullable();
            
            // Ensure the referenced tables and columns exist and match in type
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
            
            $table->timestamps(); 

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_actions');
    }
};
