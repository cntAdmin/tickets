<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('custom_id', 10)->nullable()->unique()->default('A12345BC');
            $table->string('cif', 9)->nullable();
            $table->string('fiscal_name', 100);
            $table->string('comercial_name', 100);
            $table->string('phone', 20);
            $table->string('email')->unique();
            $table->string('street')->nullable();;
            $table->string('city')->nullable();;
            $table->string('province')->nullable();;
            $table->string('country')->nullable();;
            $table->string('postcode')->nullable();;
            $table->string('shop')->nullable();;
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('deleted_by')->nullable()->default(null);
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
        Schema::dropIfExists('customers');
    }
}
