<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComeBackReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('come_back_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date')->default(now()->format('Y-m-d'));
            $table->string('employee_id')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('designation')->nullable()->index();
            $table->string('floor')->nullable()->index();
            $table->integer('absent_days')->nullable()->index();
            $table->string('reason')->nullable()->index();
            $table->string('councilor_name')->nullable()->index();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['report_date', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('come_back_reports');
    }
}
