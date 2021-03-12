<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->unsigned()->index();
            $table->bigInteger('writer_id');
            $table->string('writer_name');
            $table->string('h_title');
            $table->string('top_title');
            $table->text('top_text');
            $table->text('text');
            $table->text('image');
            $table->string('alt_image');
            $table->text('keywords');
            $table->text('last_user_view')->nullable();
            $table->bigInteger('view_count')->default("0");
            $table->text('last_user_like')->nullable();
            $table->bigInteger('like_count')->default("0");
            $table->tinyInteger('is_active')->default("0");
            $table->tinyInteger('is_best')->default("0");
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
