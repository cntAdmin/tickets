<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachment_ticket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attachment_id')->nullable()->constrained('attachments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('ticket_id')->nullable()->constrained('tickets')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('attachment_ticket');
    }
}
