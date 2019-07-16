<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
    // 'text','closer','customername','status','fee','allowcallback'
      Schema::create('messages', function (Blueprint $table) {
          $table->increments('id');
          $table->longText('text');
          $table->integer('userID');
          $table->string('closer')->nullable();
          $table->string('customername');
          $table->string('status');
          $table->integer('unAttempt')->nullable();
          $table->integer('fees');
          $table->string('allowcallback');
          $table->integer('merchantID')->nullable();
          $table->string('note')->nullable();
          $table->string('updatedBy')->default(0);
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
          Schema::dropIfExists('messages');
    }
}
