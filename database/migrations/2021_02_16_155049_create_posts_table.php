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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('title', 100);
            $table->text('description');
            $table->unsignedInteger('likes')->nullable()->default(0);
            $table->unsignedInteger('dislikes')->nullable()->default(0);
            $table->boolean('published')->nullable()->default(false);
            $table->boolean('featured')->nullable()->default(false);
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->softDeletes();
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
        Schema::dropIfExists('posts');
    }
}
