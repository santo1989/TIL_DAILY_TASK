<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('interview_date')->nullable()->index(); // Remove unique constraint
            $table->string('Candidate')->nullable()->index();
            $table->string('selected')->nullable()->index();
            $table->string('designation')->nullable()->index();
            $table->string('time_of_entrance')->nullable()->index();
            $table->string('test_taken_time')->nullable()->index();
            $table->string('test_taken_floor')->nullable()->index();
            $table->string('test_taken_by')->nullable()->index();
            $table->string('grade')->nullable()->index();
            $table->string('salary')->nullable()->index();
            $table->string('probable_date_of_joining')->nullable()->index();
            $table->string('allocated_floor')->nullable()->index();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('recruitment_summaries');
    }
}
