<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {

            $table->foreignId('entity_id');
            $table->String('article');
            $table->timestamps();

        });
        Schema::create('photos', function(Blueprint $table) {
            $table->id('id');
            $table->foreignId('entity_id');
            $table->text('caption')->nullable();
            $table->String('photo');
            $table->timestamps();

        });
        Schema::create('videos', function(Blueprint $table){

            $table->foreignId('entity_id');
            $table->text('caption')->nullable();
            $table->integer('video');
            $table->timestamps();

        });

        Schema::create('entity_likes', function (Blueprint $table){
            $table->foreignId('entity_id');
            $table->Integer('user_id');
            $table->unique(['entity_id','user_id']);
            $table->timestamps();

        });
        Schema::create('entity_comments', function (Blueprint $table){

            $table->id('id');
            $table->unsignedBigInteger('parent_id')->nullable()->default(null);
            $table->foreignid('entity_id');
            $table->foreignid('user_id');
            $table->text('comment');
            $table->timestamps();
        });
        Schema::create('entities', function (Blueprint $table){
            $table->id('id');
            $table->foreignId('parent_id')->nullable()->default(null);
            $table->foreignId('user_id');
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

        Schema::dropIfExists('videos');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('entities');
        Schema::dropIfExists('entity_likes');
        Schema::dropIfExists('entity_comments');
        Schema::dropIfExists('photos');

    }
}
