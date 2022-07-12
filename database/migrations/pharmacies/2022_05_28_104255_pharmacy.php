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
        Schema::connection('pharmacy')->create(AU_DB_PREFIX.'pharmacies',function (Blueprint $table){
            $table->id();
            $table->uuid('partner_id')->index();
            $table->string('title',150);
            $table->string('phone',16);
            $table->string('mobile',16);
            $table->string('email',150);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::connection('pharmacy')->create(AU_DB_PREFIX.'products', function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->integer('pharmacy_id')->index();
            $table->string('title',100);
            $table->decimal('price',15,2)->unsigned()->default(0)->change();
            $table->integer('quantity')->default(0);
            $table->string('bar_code')->unique();
            $table->decimal('discount',15,2)->unsigned()->default(0);
        });
        Schema::connection('pharmacy')->create(AU_DB_PREFIX.'categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        Schema::connection('pharmacy')->create(AU_DB_PREFIX.'suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('company');
            $table->string('address');
            $table->string('product');
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::connection('pharmacy')->create(AU_DB_PREFIX.'purchases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('category_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('price');
            $table->string('quantity');
            $table->date('expiry_date');
            $table->string('image')->nullable();
            $table->timestamps();
        });
        Schema::connection('pharmacy')->create(AU_DB_PREFIX.'sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('product_id')->nullable();
            $table->integer('quantity');
            $table->double('total_price',13,3);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::connection('pharmacy')->create(AU_DB_PREFIX.'sale_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('sale_id');
            $table->double('discount',13,3);
            $table->double('net',13,3);
            $table->json('info_payment');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::connection('pharmacy')->create(AU_DB_PREFIX.'notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('pharmacy_id')->unsigned();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
        Schema::connection('pharmacy')->create(AU_DB_PREFIX.'positions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
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
        Schema::dropIfExists(AU_DB_PREFIX.'pharmacies');
        Schema::dropIfExists(AU_DB_PREFIX.'products');
        Schema::dropIfExists(AU_DB_PREFIX.'categories');
        Schema::dropIfExists(AU_DB_PREFIX.'purchases');
        Schema::dropIfExists(AU_DB_PREFIX.'sales');
        Schema::dropIfExists(AU_DB_PREFIX.'notifications');
        Schema::dropIfExists(AU_DB_PREFIX.'positions');
        Schema::dropIfExists(AU_DB_PREFIX.'supplier');
        Schema::dropIfExists(AU_DB_PREFIX.'sale_details');
    }
};
