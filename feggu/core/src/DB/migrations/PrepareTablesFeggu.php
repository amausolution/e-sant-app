<?php
namespace Feggu\Core\DB\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PrepareTablesFeggu extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Drop table if exist
        $this->down();

        Schema::create(
            AU_DB_PREFIX.'feggu_banner',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('title', 255)->nullable();
                $table->string('image', 255)->nullable();
                $table->string('url', 100)->nullable();
                $table->string('target', 50)->nullable();
                $table->text('html')->nullable();
                $table->tinyInteger('status')->default(0);
                $table->integer('sort')->default(0);
                $table->integer('click')->default(0);
                $table->string('type', 20)->index();
                $table->timestamps();
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_banner_type',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('code', 100)->unique();
                $table->string('name', 100);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_email_template',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 50);
                $table->string('group', 50);
                $table->text('text');
                $table->integer('partner_id')->default(1)->index();
                $table->tinyInteger('status')->default(0);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_language',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100);
                $table->string('code', 50)->unique();
                $table->string('icon', 100)->nullable();
                $table->tinyInteger('status')->default(0);
                $table->tinyInteger('rtl')->nullable()->default(0)->comment('Layout RTL');
                $table->integer('sort')->default(0);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_partner_block',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100);
                $table->string('position', 100);
                $table->string('page', 200)->nullable();
                $table->string('type', 200);
                $table->text('text')->nullable();
                $table->tinyInteger('status')->default(0);
                $table->integer('sort')->default(0);
                $table->integer('partner_id')->default(1)->index();
                $table->string('template', '50')->index();
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_layout_page',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('key', 100)->unique();
                $table->string('name', 100);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_layout_position',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('key', 100)->unique();
                $table->string('name', 100);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_link',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100);
                $table->string('url', 100);
                $table->string('target', 100);
                $table->string('group', 100);
                $table->string('module', 100)->nullable();
                $table->tinyInteger('status')->default(0);
                $table->integer('sort')->default(0);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_link_partner',
            function (Blueprint $table) {
                $table->integer('link_id');
                $table->integer('partner_id');
                $table->primary(['link_id', 'partner_id']);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_password_resets',
            function (Blueprint $table) {
                $table->string('email', 150);
                $table->string('token', 255);
                $table->dateTime('created_at');
                $table->index('email');
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_brand',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100);
                $table->string('alias', 120)->index();
                $table->string('image', 255)->nullable();
                $table->string('url', 100)->nullable();
                $table->tinyInteger('status')->default(0);
                $table->integer('sort')->default(0);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_currency',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100);
                $table->string('code', 10)->unique();
                $table->string('symbol', 10);
                $table->float('exchange_rate');
                $table->tinyInteger('precision')->default(2);
                $table->tinyInteger('symbol_first')->default(0);
                $table->string('thousands')->default(',');
                $table->tinyInteger('status')->default(0);
                $table->integer('sort')->default(0);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_page',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('image', 255)->nullable();
                $table->string('alias', 120)->index();
                $table->integer('status')->default(0);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_page_partner',
            function (Blueprint $table) {
                $table->integer('page_id');
                $table->integer('partner_id');
                $table->primary(['page_id', 'partner_id']);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_page_description',
            function (Blueprint $table) {
                $table->integer('page_id');
                $table->string('lang', 10)->index();
                $table->string('title', 200)->nullable();
                $table->string('keyword', 200)->nullable();
                $table->string('description', 300)->nullable();
                $table->text('content')->nullable();
                $table->unique(['page_id', 'lang']);
            }
        );


        Schema::create(
            AU_DB_PREFIX.'feggu_country',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('code', 10)->unique();
                $table->string('name', 100);
                $table->string('dial_code', 10);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_region',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('code', 10)->unique();
                $table->string('name', 100);
                $table->string('country', 5);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_department',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('code', 10)->unique();
                $table->string('name', 100);
                $table->string('region',5);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_district',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('department')->index();
                $table->string('name', 100);
            }
        );


        Schema::create(
            AU_DB_PREFIX.'feggu_category_partner',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('image', 255)->nullable();
                $table->string('alias', 120)->index();
                $table->tinyInteger('status')->default(0);
                $table->integer('sort')->default(0);
                $table->timestamp('deleted_at')->nullable();
            }
        );

        Schema::create(
            AU_DB_PREFIX.'feggu_category_partner_description',
            function (Blueprint $table) {
                $table->integer('category_id');
                $table->string('lang', 10)->index();
                $table->string('title', 200)->nullable();
                $table->string('description', 300)->nullable();
                $table->unique(['category_id', 'lang']);
            }
        );


        Schema::create(
            AU_DB_PREFIX.'feggu_news',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('image', 200)->nullable();
                $table->string('alias', 120)->index();
                $table->integer('sort')->default(0);
                $table->tinyInteger('status')->default(0);
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_news_description',
            function (Blueprint $table) {
                $table->integer('news_id');
                $table->string('lang', 10);
                $table->string('title', 200)->nullable();
                $table->string('keyword', 200)->nullable();
                $table->string('description', 300)->nullable();
                $table->text('content')->nullable();
                $table->unique(['news_id', 'lang']);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_partner_patient',
            function (Blueprint $table) {
                $table->integer('partner_id');
                $table->integer('patient_id');
                $table->primary(['partner_id', 'patient_id']);
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_patient_prescription',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('patient_id');
                $table->integer('consultation_id');
                $table->integer('prescription_id');
            }
        );


        Schema::create(
            AU_DB_PREFIX.'feggu_sessions',
            function ($table) {
                $table->string('id', 100)->unique();
                $table->unsignedInteger('customer_id')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->text('payload');
                $table->integer('last_activity');
            }
        );
        //Passport
        Schema::create('oauth_auth_codes', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->uuid('user_id')->index();
            $table->uuid('client_id');
            $table->text('scopes')->nullable();
            $table->boolean('revoked');
            $table->dateTime('expires_at')->nullable();
        });
        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->uuid('user_id')->nullable()->index();
            $table->uuid('client_id');
            $table->string('name')->nullable();
            $table->text('scopes')->nullable();
            $table->boolean('revoked');
            $table->timestamps();
            $table->dateTime('expires_at')->nullable();
        });
        Schema::create('oauth_refresh_tokens', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('access_token_id', 100)->index();
            $table->boolean('revoked');
            $table->dateTime('expires_at')->nullable();
        });
        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable()->index();
            $table->string('name');
            $table->string('secret', 100)->nullable();
            $table->string('provider')->nullable();
            $table->text('redirect');
            $table->boolean('personal_access_client');
            $table->boolean('password_client');
            $table->boolean('revoked');
            $table->timestamps();
        });
        Schema::create('oauth_personal_access_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('client_id');
            $table->timestamps();
        });
        Schema::create(AU_DB_PREFIX.'api_connection', function (Blueprint $table) {
                $table->increments('id');
                $table->string('description', 255);
                $table->string('apiconnection', 50)->unique();
                $table->string('apikey', 128);
                $table->date('expire')->nullable();
                $table->datetime('last_active')->nullable();
                $table->tinyInteger('status')->default(0);
            }
        );

        Schema::create(
            'jobs',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('queue', 150)->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            }
        );
        Schema::create(
            'failed_jobs',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('uuid', 200)->nullable()->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_partner_css',
            function (Blueprint $table) {
                $table->text('css');
                $table->integer('partner_id')->unique();
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_custom_field',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('type', 50)->index()->comment('product, customer');
                $table->string('code', 100)->index();
                $table->string('name', 250);
                $table->integer('required')->default(0);
                $table->integer('status')->default(1);
                $table->string('option', 50)->nullable()->comment('radio, select, input');
                $table->string('default', 250)->nullable()->comment('{"value1":"name1", "value2":"name2"}');
            }
        );
        Schema::create(
            AU_DB_PREFIX.'feggu_custom_field_detail',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('custom_field_id')->index();
                $table->integer('rel_id')->index();
                $table->text('text')->nullable();
            }
        );
        Schema::create(
            AU_DB_PREFIX.'languages',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('code', 100)->index();
                $table->text('text')->nullable();
                $table->string('position', 100)->index();
                $table->string('location', 10)->index();
                $table->unique(['code', 'location']);
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
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_banner');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_banner_type');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_email_template');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_language');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_partner_block');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_layout_page');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_layout_position');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_link');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_password_resets');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_shipping_standard');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_api');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_api_process');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_brand');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_currency');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_page');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_page_description');

        Schema::dropIfExists(AU_DB_PREFIX.'feggu_subscribe');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_country');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_region');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_department');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_district');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_news');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_news_description');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_sessions');
        //Passport
        Schema::dropIfExists('oauth_auth_codes');
        Schema::dropIfExists('oauth_access_tokens');
        Schema::dropIfExists('oauth_refresh_tokens');
        Schema::dropIfExists('oauth_clients');
        Schema::dropIfExists('oauth_personal_access_clients');
        //Api connection
        Schema::dropIfExists(AU_DB_PREFIX.'api_connection');
        //Job
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_partner_css');
        //Custom field
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_custom_field');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_custom_field_detail');
        //Languages
        Schema::dropIfExists(AU_DB_PREFIX.'languages');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_link_partner');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_page_partner');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_category_partner');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_category_partner_description');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_partner_patient');
        Schema::dropIfExists(AU_DB_PREFIX.'feggu_patient_prescription');



    }
}
