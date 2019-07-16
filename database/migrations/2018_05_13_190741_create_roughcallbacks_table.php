<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoughcallbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
  
      Schema::create('roughcallbacks', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('message_id');
          $table->dateTime('date_time');
          $table->char('type',20);
          $table->rememberToken();
          $table->timestamps();
      });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
          Schema::dropIfExists('roughcallbacks');
    }
}
