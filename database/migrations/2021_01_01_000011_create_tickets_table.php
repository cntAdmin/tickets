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
        if(Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $table) {
                $table->id();
                $table->string('custom_id', 100)->unique('custom_id');
                $table->foreignId('customer_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
                $table->foreignId('department_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
                $table->string('frame_id', 100);
                $table->string('plate', 100);
                $table->string('brand', 100);
                $table->string('model', 100);
                $table->string('engine_type', 100);
                $table->text('subject');
                $table->text('description');
                $table->text('tests_done');
                $table->string('ask_for', 50);
                $table->boolean('knowledge_base');
                $table->foreignId('ticket_status_id')->default(1)->constrained()->onUpdate('cascade')->onDelete('cascade');
                $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
                $table->foreignId('deleted_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
                $table->softDeletes();
                $table->timestamps();
            });
        }
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
