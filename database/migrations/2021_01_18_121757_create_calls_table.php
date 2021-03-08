<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('src', 80)->nullable();
            $table->string('dst', 80)->nullable();
            $table->string('dcontext', 255)->nullable();
            $table->string('clid', 255)->nullable();
            $table->string('channel', 255)->nullable();
            $table->string('dstchannel', 255)->nullable();
            $table->string('lastapp', 255)->nullable();
            $table->string('lastdata', 255)->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('answer')->nullable();
            $table->dateTime('end')->nullable();
            $table->unsignedSmallInteger('duration')->nullable();
            $table->unsignedSmallInteger('billsec')->nullable();
            $table->string('disposition', 50)->nullable();
            $table->string('userfield', 255)->nullable();
            $table->string('uniquefield', 255)->nullable();
            $table->string('linkedid', 255)->nullable();
            $table->unsignedTinyInteger('is_incoming');
            $table->unsignedTinyInteger('is_outgoing');
            $table->unsignedTinyInteger('is_to_billing');
            $table->unsignedTinyInteger('is_recorded');
            $table->unsignedTinyInteger('rating_status');
            $table->string('src_extension', 15)->nullable();
            $table->string('dst_extension', 15)->nullable();
            $table->string('src_number', 15)->nullable();
            $table->string('dst_number', 15)->nullable();
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
        Schema::dropIfExists('calls');
    }
}
