<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('frame_id', 100);
            $table->string('plate', 100);
            $table->string('brand', 100);
            $table->string('model', 100);
            $table->foreignId('engine_type_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->text('subject');
            $table->text('description');
            $table->text('tests_done');
            $table->text('ask_for');
            $table->text('files');
            $table->boolean('is_head');
            $table->boolean('knowledge_base');
            $table->string('status', 100)->nullable()->default('opened');
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
        Schema::dropIfExists('tickets');
    }
}
