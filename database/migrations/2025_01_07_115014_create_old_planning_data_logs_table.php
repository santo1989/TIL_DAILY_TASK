<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOldPlanningDataLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_planning_data_logs', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('no action'); // Change to 'no action'
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('no action'); // Change to 'no action'
            $table->string('division_name')->nullable();
            $table->string('company_name')->nullable();
            $table->unsignedBigInteger('planning_data_id')->nullable();
            $table->date('date')->nullable();
            $table->date('old_date')->nullable(); 
            $table->string('old_floor')->nullable(); 
            $table->string('old_line')->nullable(); 
            $table->string('old_section')->nullable(); 
            $table->string('old_buyer')->nullable(); 
            $table->string('old_buyer_id')->nullable(); 
            $table->string('old_style')->nullable(); 
            $table->string('old_item')->nullable(); 
            $table->float('old_smv')->nullable(); 
            $table->integer('old_order_qty')->nullable(); 
            $table->integer('old_allocate_qty')->nullable(); 
            $table->date('old_sewing_start_time')->nullable(); 
            $table->date('old_sewing_end_time')->nullable();
            $table->integer('old_required_man_power')->nullable();
            $table->float('old_average_working_our')->nullable();
            $table->float('old_expected_efficiency')->nullable();
            $table->float('old_actual_efficiency')->nullable();
            $table->text('old_remarks')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action'); // Change to 'no action' 
            $table->string('data_edit_by')->nullable();
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
        Schema::dropIfExists('old_planning_data_logs');
    }
}
