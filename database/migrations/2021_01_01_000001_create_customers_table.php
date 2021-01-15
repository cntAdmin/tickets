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
            $table->string('cif', 9);
            $table->string('fiscal_name', 100);
            $table->string('comercial_name', 100);
            $table->string('phone', 20);
            $table->string('email')->unique();
            $table->string('street');
            $table->string('town');
            $table->string('city');
            $table->string('country');
            $table->string('postcode');
            $table->string('shop');
            $table->boolean('is_active')->default(false);
            $table->unsignedBigInteger('deleted_by')->nullable();
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
