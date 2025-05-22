<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorAbsentAnalysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operator_absent_analyses', function (Blueprint $table) {
            $table->id();
            $table->date('report_date')->default(now()->format('Y-m-d'))->index();
            $table->string('floor')->nullable()->index();  
            $table->integer('line')->nullable()->index();
            $table->string('employee_id')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('designation')->nullable()->index();
            $table->date('join_date')->nullable()->index();
            $table->date('last_p_date')->nullable()->index();
            $table->integer('total_absent_days')->nullable()->index();
            $table->string('absent_reason')->nullable()->index();
            $table->date('come_back')->nullable()->index();
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
        Schema::dropIfExists('operator_absent_analyses');
    }
}
