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
            AU_DB_PREFIX.'hospitalisation_consultation',
            function (Blueprint $table) {
                $table->uuid('hosp_id');
                $table->uuid('consultation_id');
                $table->primary(['hosp_id', 'consultation_id']);
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
        Schema::dropIfExists(AU_DB_PREFIX.'hospitalisation_consultation');
    }
};
