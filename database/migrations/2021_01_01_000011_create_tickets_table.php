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
            $table->string('custom_id', 100)->unique('custom_id');
            $table->foreignId('customer_id')->nullable()->constrained()->onUpdate('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onUpdate('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onUpdate('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onUpdate('cascade');
            $table->string('frame_id', 100);
            $table->string('plate', 100);
            $table->foreignId('brand_id')->nullable()->constrained()->onUpdate('cascade');
            $table->foreignId('car_model_id')->nullable()->constrained()->onUpdate('cascade');
            $table->string('engine_type', 100);
            $table->text('subject');
            $table->text('description');
            $table->text('tests_done');
            $table->string('ask_for', 50);
            $table->boolean('knowledge_base');
            $table->foreignId('ticket_status_id')->default(1)->constrained()->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onUpdate('cascade');
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
        Schema::dropIfExists('tickets');
    }
}
