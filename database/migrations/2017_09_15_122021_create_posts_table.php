<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('parent_id');
            $table->string('title');
            $table->string('content');
            $table->string('type');
            $table->string('status_id');
            $table->string('slug');
            $table->string('visible');
            $table->string('thumbnail');
            $table->string('article');
            $table->string('publish_time');
            $table->string('keywords');
            $table->string('description');
            $table->integer('author_id');
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
        Schema::drop('post');
    }
}
