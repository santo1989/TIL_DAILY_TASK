<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_reports', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // lunch_out, late_comer, to_be_absent, on_leave
            $table->date('report_date')->default(now()->format('Y-m-d'))->index();
            $table->string('employee_id')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('designation')->nullable()->index();
            $table->string('floor')->nullable()->index();
            $table->time('in_time')->nullable()->index();
            $table->string('reason')->nullable()->index();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['type', 'report_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_reports');
    }
}
