<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('score_kpis', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dept_id')->unsigned();
            $table->bigInteger('kpi_id')->unsigned();
            $table->bigInteger('realisation')->unsigned();
            $table->decimal('score', total: 8, places: 2);
            // $table->date('kpi_date')->DATE_FORMAT(NOW(), '%Y-%m');

            $table->foreign('dept_id')->references('id')->on('departments');
            $table->foreign('kpi_id')->references('id')->on('kpis');
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
        Schema::dropIfExists('score_kpis');
    }
};
