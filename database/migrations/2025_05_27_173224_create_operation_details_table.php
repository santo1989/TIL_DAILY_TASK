<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_details', function (Blueprint $table) {
            $table->id();
            $table->string('activity')->nullable()->index();
            $table->decimal('floor_1', 8, 3)->nullable()->index();
            $table->decimal('floor_2', 8, 3)->nullable()->index();
            $table->decimal('floor_3', 8, 3)->nullable()->index();
            $table->decimal('floor_4', 8, 3)->nullable()->index();
            $table->decimal('floor_5', 8, 3)->nullable()->index();
            $table->decimal('result', 8, 3)->nullable()->index();
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
        Schema::dropIfExists('operation_details');
    }
}
