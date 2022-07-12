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
        Schema::connection('consultation')->create(
            AU_DB_PREFIX.'transfer_patient',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('hosp_id');
                $table->uuid('consultation_id');
            }
        );
        Schema::connection('consultation')->create(
            AU_DB_PREFIX.'transfer_details',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('transfer_id');
                $table->string('ambulance_number');
                $table->text('note');
                $table->bigInteger('doctor_id');
                $table->bigInteger('user_id');
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::connection('consultation')->create(
            AU_DB_PREFIX.'hospitalisation_details',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('hospitalisation_id');
                $table->string('title');
                $table->text('value');
                $table->bigInteger('user_id');
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::connection('consultation')->create(
            AU_DB_PREFIX.'hospitalisation_tasks',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('hospitalisation_id');
                $table->string('title');
                $table->text('value');
                $table->bigInteger('doctor_id');
                $table->bigInteger('user_id');
                $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists(AU_DB_PREFIX.'transfer_patient');
        Schema::dropIfExists(AU_DB_PREFIX.'transfer_details');
        Schema::dropIfExists(AU_DB_PREFIX.'hospitalisation_detail');
        Schema::dropIfExists(AU_DB_PREFIX.'hospitalisation_tasks');
    }
};
