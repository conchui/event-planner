<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('EVENT', function (Blueprint $table) {
            $table->id('ID');
            $table->string('NAME');
            $table->date('START_DATE');
            $table->date('END_DATE');
            $table->string('ACTIVE_DAYS');
            $table->timestamps('TIMESTAMP');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
