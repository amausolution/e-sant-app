<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('laboratory')->create(
            AU_DB_PREFIX.'laboratory',
            function (Blueprint $table) {
                $table->id();
                $table->uuid('partner');
                $table->string('phone',16)->nullable();
                $table->string('mobile',16)->nullable();
                $table->string('email',200)->nullable();
                $table->tinyInteger('status')->default(1);
                $table->tinyInteger('type')->default(1);
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::connection('laboratory')->create(
            AU_DB_PREFIX.'type_analyse',
            function (Blueprint $table) {
                $table->id();
                $table->bigInteger('laboratory_id');
                $table->json('analyse');
                $table->integer('price');
                $table->tinyInteger('status')->default(1);
                $table->tinyInteger('take_assurance')->default(0);
                $table->string('duration',50)->nullable();
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(AU_DB_PREFIX.'laboratory');
        Schema::dropIfExists(AU_DB_PREFIX.'type_analyse');
    }
};
