<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->string('email', 50);
            $table->string('password');
            $table->integer('age');
            $table->string('contact_number')->nullable();
            $table->string('symptoms')->nullable();
            $table->date('first_symptom_date')->nullable();
            $table->boolean('is_tested')->nullable();
            $table->date('test_date')->nullable();
            $table->boolean('is_recovered')->nullable();
            $table->date('recovery_date')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('patients');
    }
}
