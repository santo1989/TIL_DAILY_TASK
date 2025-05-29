<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ot_achievements', function (Blueprint $table) {
            $table->id();
            $table->string('floor')->nullable()->index();
            $table->integer('two_hours_ot_persons')->nullable()->index();
            $table->integer('above_two_hours_ot_persons');
            $table->decimal('achievement', 12, 2)->nullable()->index();
            $table->text('remarks')->nullable();
            $table->date('report_date')->nullable()->index();
            $table->timestamps();

            $table->index(['floor', 'report_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ot_achievements');
    }
}
