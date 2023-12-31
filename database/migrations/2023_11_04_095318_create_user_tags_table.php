<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_tags', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('tag_id')->unsigned();

            $table->primary(['user_id', 'tag_id']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_tags');
    }
};
