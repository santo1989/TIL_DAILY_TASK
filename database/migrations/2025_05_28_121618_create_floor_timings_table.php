<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloorTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('floor_timings', function (Blueprint $table) {
            $table->id();
            $table->string('floor')->nullable()->index();
            $table->time('starting_time')->nullable()->index();
            $table->json('starting_responsible')->nullable(); // Store as JSON array
            $table->time('closing_time')->nullable()->index();
            $table->json('closing_responsible')->nullable(); // Store as JSON array
            $table->text('remarks')->nullable();
            $table->date('report_date')->nullable()->index();
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
        Schema::dropIfExists('floor_timings');
    }
}
