<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planning_data', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('no action'); // Change to 'no action'
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('no action'); // Change to 'no action'
            $table->string('division_name')->nullable();
            $table->string('company_name')->nullable();
            
            $table->date('date')->nullable();
            $table->string('floor')->nullable();
            $table->string('line')->nullable();
            $table->string('section')->nullable();
            $table->string('buyer')->nullable();
            $table->string('buyer_id')->nullable();
            $table->string('style')->nullable();
            $table->string('item')->nullable();
            $table->float('smv')->nullable();
            $table->integer('order_qty')->nullable();
            $table->integer('allocate_qty')->nullable();
            $table->date('sewing_start_time')->nullable();
            $table->date('sewing_end_time')->nullable(); 
            $table->integer('required_man_power')->nullable();
            $table->float('average_working_our')->nullable();
            $table->float('expected_efficiency')->nullable();
            $table->float('actual_efficiency')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action'); // Change to 'no action'
            $table->string('data_entry_by')->nullable();
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
        Schema::dropIfExists('planning_data');
    }
}
