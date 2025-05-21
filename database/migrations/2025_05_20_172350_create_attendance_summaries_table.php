<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('report_date')->default(now()->format('Y-m-d'));
            $table->string('floor')->nullable()->index();
            $table->integer('onroll')->nullable()->index();
            $table->integer('present')->nullable()->index();
            $table->integer('absent')->nullable()->index();
            $table->integer('leave')->nullable()->index();
            $table->integer('ml')->nullable()->index();
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Prevent duplicate entries for same date and floor
            $table->unique(['report_date', 'floor']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_summaries');
    }
}
