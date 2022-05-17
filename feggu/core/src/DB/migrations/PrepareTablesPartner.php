<?php
namespace Feggu\Core\DB\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PrepareTablesPartner extends Migration
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
        Schema::create(AU_DB_PREFIX . 'menu_partner', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->integer('sort')->default(0);
            $table->string('title', 100);
            $table->string('icon', 50);
            $table->string('uri', 255)->nullable();
            $table->integer('type')->default(0);
            $table->integer('hidden')->default(0);
            $table->string('key', 50)->unique()->nullable();
            $table->timestamps();
        });
        Schema::create(AU_DB_PREFIX . 'partner_menu_partner', function (Blueprint $table) {
            $table->integer('menu_id');
            $table->integer('partner_id');
            $table->primary(['menu_id', 'partner_id']);
            $table->tinyInteger('status')->default(1);
            $table->integer('sort')->default(0);

        });

        Schema::create(AU_DB_PREFIX . 'partner_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 100)->unique();
            $table->string('password', 60);
            $table->string('matricule', 60);
            $table->string('cin', 20);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('profession', 255);
            $table->string('education', 255)->nullable()->comment('niveau d\'étude, qualification');
            $table->date('birthday');
            $table->string('email', 150)->unique();
            $table->integer('phone')->unique();
            $table->string('avatar', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('theme', 100)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('gender')->default(1)->comment('1:homme,2:femme');
            $table->integer('partner_id');
            $table->timestamps();
            $table->timestamp('deleted_at');
        });
        Schema::create(AU_DB_PREFIX . 'partner_employer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('matricule', 60);
            $table->string('cin', 20);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('profession', 255);
            $table->string('education', 255)->nullable()->comment('niveau d\'étude, qualification');
            $table->date('birthday');
            $table->string('email', 150)->unique();
            $table->integer('phone')->unique();
            $table->string('address', 255)->nullable();
            $table->string('type_contra', 255)->nullable();
            $table->integer('time')->nullable();
            $table->tinyInteger('gender')->default(1)->comment('1:homme,2:femme');
            $table->integer('partner_id');
            $table->timestamps();
            $table->timestamp('deleted_at');
        });

        Schema::create(AU_DB_PREFIX . 'user_password_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('partner_user_id');
            $table->string('password', 50);
            $table->timestamps();
        });
        Schema::create(AU_DB_PREFIX . 'partner_task', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partner_user_id', 50);
            $table->string('title', 50);
            $table->text('body');
            $table->tinyInteger('sort');
            $table->tinyInteger('status')->comment('0:new,1:done,2:pending');
            $table->dateTime('todo_at');
            $table->timestamps();
        });
        Schema::create(AU_DB_PREFIX . 'partner_role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('slug', 50)->unique();
            $table->timestamps();
        });
        Schema::create(AU_DB_PREFIX . 'partner_permission', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->text('http_uri')->nullable();
            $table->timestamps();
        });
        Schema::create(AU_DB_PREFIX . 'partner_role_user', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('user_id');
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
        });
        Schema::create(AU_DB_PREFIX . 'partner_role_permission', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
            $table->primary(['role_id', 'permission_id']);
        });
        Schema::create(AU_DB_PREFIX . 'partner_user_permission', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('permission_id');
            $table->index(['user_id', 'permission_id']);
            $table->timestamps();
            $table->primary(['user_id', 'permission_id']);
        });
        Schema::create(AU_DB_PREFIX . 'partner_template', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('doctor_from');
            $table->integer('doctor_to');
            $table->text('body');
            $table->string('joint',255);
            $table->tinyInteger('view')->default(0);
            $table->timestamps();
        });
        Schema::create(AU_DB_PREFIX . 'partner_user_chat', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from');
            $table->integer('to');
            $table->text('body');
            $table->integer('status', 0)->default(0);
            $table->timestamps();
        });
        Schema::create(AU_DB_PREFIX . 'partner_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('partner_id');
            $table->string('path');
            $table->string('method', 10);
            $table->string('ip');
            $table->string('user_agent')->nullable();
            $table->text('input');
            $table->index('user_id');
            $table->index('partner_id');
            $table->timestamps();
        });
        Schema::create(AU_DB_PREFIX . 'partner_user_folder', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('folder_parent')->unique();
            $table->string('folder_child')->unique();
            $table->timestamps();
        });
        //patient
        Schema::create(
            AU_DB_PREFIX.'patient',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('matricule', 100);
                $table->string('first_name', 100);
                $table->string('last_name', 100)->nullable();
                $table->string('email', 150)->nullable();
                $table->string('blood_group', 5)->nullable();
                $table->tinyInteger('sex')->nullable()->comment('0:women, 1:men');
                $table->tinyInteger('civility')->nullable()->comment('0:mr, 1:md, 2:mll');
                $table->date('birthday')->nullable();
                $table->string('password', 100)->nullable();
                $table->string('postcode', 10)->nullable();
                $table->string('type_piece', 10)->nullable();
                $table->string('number_piece', 40)->nullable();
                $table->string('assurance_number',100)->nullable();
                $table->string('address', 100)->nullable();
                $table->string('department', 100)->nullable();
                $table->string('region', 100)->nullable();
                $table->string('district', 100)->nullable();
                $table->string('country', 10)->nullable()->default('SN');
                $table->string('phone', 20)->nullable();
                $table->string('phone2', 20)->nullable();
                $table->string('phone_urgency', 20)->nullable();
                $table->string('remember_token', 100)->nullable();
                $table->tinyInteger('status')->default(1);
                $table->text('two_factor_recovery_codes');
                $table->text('two_factor_secret');
                $table->tinyInteger('group')->default(1);
                $table->timestamp('email_verified_at', $precision = 0)->nullable();
                $table->timestamp('phone_verified_at', $precision = 0)->nullable();
                $table->timestamps();

            }
        );
        Schema::create(
            AU_DB_PREFIX.'patient_detail',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('patient_id')->index();
                $table->string('piece_recto',255)->nullable();
                $table->string('piece_verso',255)->nullable();
                $table->text('observation')->nullable();

            }
        );//detail

        Schema::create(
            AU_DB_PREFIX.'war_hours',
            function (Blueprint $table) {
                $table->increments('id');
                $table->bigInteger('doctor_id');
                $table->bigInteger('hospital_id');
                $table->string('day',20);
                $table->string('hour_start',50);
                $table->string('hour_end',50);
            }
        );//doctor emploit du temps
        Schema::create(
            AU_DB_PREFIX.'doctor_speciality',
            function (Blueprint $table) {
                $table->increments('id');
                $table->bigInteger('doctor_id');
                $table->string('speciality',500)->index();
            }
        );//specialité
        Schema::create(
            AU_DB_PREFIX.'patient_allergy',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('patient_id')->index();
                $table->string('allergy',255);
            }
        );//allergy
        Schema::create(
            AU_DB_PREFIX.'patient_pathology',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('patient_id')->index();
                $table->string('Pathology',255)->nullable();
                $table->string('level',255)->nullable();
                $table->text('observation')->nullable();
                $table->integer('doctor_id');

            }
        );//pathologie
        Schema::create(
            AU_DB_PREFIX.'subscribe',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('email', 120)->index();
                $table->string('phone', 20)->nullable();
                $table->string('content', 300)->nullable();
                $table->tinyInteger('status')->default(1);
                $table->integer('partner_id')->default(1)->index();
                $table->timestamps();
            }
        );//souscription

      /*  //hospital
        Schema::create(
            AU_DB_PREFIX.'hospital',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('hospital_name',255);
                $table->string('responsible',255);
                $table->string('address',255);
                $table->string('region',50);
                $table->string('department',100);
                $table->string('district',100);
                $table->tinyInteger('star');
                $table->string('type',100)->comment('clinique,centre de santé hopital ....');
                $table->text('map');
            }
        );*/
        Schema::create(
            AU_DB_PREFIX.'hospital_room',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('hospital_id');
                $table->string('room_number',100);
                $table->integer('number_bet');
                $table->integer('price');
                $table->tinyInteger('status');
                $table->string('level',50)->comment('rez de chausse 1er étage ...');
                $table->string('class',2)->comment('classe A cabinet, B chambre ...');
                $table->tinyInteger('clim')->comment('0:sans clim, 1:avec clim');
                $table->tinyInteger('bet_accompanying')->default(0);
            }
        );//room
        Schema::create(
            AU_DB_PREFIX.'hospital_assurance',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('hospital_id');
                $table->string('assurance',100);
            }
        );//assurance

        Schema::create(
            AU_DB_PREFIX.'hospital_detail',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('hospital_id');
                $table->text('hour_visit');
                $table->text('about');
                $table->integer('nbr_ambulance');
                $table->string('hospital_plant',255);
                $table->tinyInteger('lab')->default(0);
                $table->tinyInteger('pharmacy_intern')->default(1);
                $table->tinyInteger('parking')->default(0);
                $table->tinyInteger('take_assurance')->default(0);
                $table->string('near_pharmacy',255);
                $table->string('phones',255);

            }
        );//detail
        Schema::create(
            AU_DB_PREFIX.'hospital_patient',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('hospital_id');
                $table->integer('patient_id');
                $table->string('health',200);
                $table->string('level',200);
                $table->tinyInteger('urgency')->default(0);
                $table->timestamps();
            }
        );//patient
        Schema::create(
            AU_DB_PREFIX.'doctor_patient',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('doctor_id');
                $table->integer('patient_id');
                $table->timestamps();
            }
        );//patient
        Schema::create(
            AU_DB_PREFIX.'hospital_department',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('hospital_id');
                $table->string('department',100);
                $table->timestamp('deleted_at');
                $table->timestamps();
            }
        );//service
        //hospitalisation
        Schema::create(
            AU_DB_PREFIX.'hospital_hospitalisation',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('hospital_id');
                $table->bigInteger('patient_id');
                $table->string('room',200);
                $table->string('bet_number',10);
                $table->timestamp('date_in');
                $table->timestamp('date_out');
                $table->string('accompanying',150);
                $table->string('piece_guarantor');
                $table->timestamps();
            }
        );//hospitalisation
        Schema::create(
            AU_DB_PREFIX.'hospitalisation_track',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('hospitalisation_id');
                $table->integer('doctor_id');
                $table->text('fiche');
                $table->timestamps();
            }
        );//hospitalisation_fiche
        Schema::create(
            AU_DB_PREFIX.'hosp_prescription',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('hospitalisation_id');
                $table->integer('doctor_id');
                $table->text('note');
                $table->timestamps();
            }
        );//hospitalisation_prescription
        Schema::create(
            AU_DB_PREFIX.'hosp_prescription_detail',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('prescription_id')->index();
                $table->integer('pharmacy_id')->index();
                $table->text('item');
                $table->integer('quantity');
                $table->string('dosage');
                $table->timestamps();
            }
        );//hospitalisation_prescription_detail
        Schema::create(
            AU_DB_PREFIX.'hosp_analyse',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('hospitalisation_id');
                $table->integer('doctor_id');
                $table->text('note');
                $table->timestamps();
            }
        );//hospitalisation_analyse
        Schema::create(
            AU_DB_PREFIX.'hosp_analyse_detail',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('analyse_id')->index();
                $table->integer('lab_id')->index();
                $table->text('item');
                $table->text('comment');
                $table->string('result');
                $table->timestamps();
            }
        );//hospitalisation_analyse_detail
        Schema::create(
            AU_DB_PREFIX.'hosp_payment',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('hospitalisation_id')->index();
                $table->integer('patient_id')->index();
                $table->integer('total');
                $table->integer('receive');
                $table->integer('balance');
                $table->integer('discount');
                $table->string('type_intervention')->comment('consultation,hospitalisation,analyse,thérapie');
                $table->text('payment_method');
                $table->text('payed_by');
                $table->text('payed_by_piece');
                $table->timestamp('deleted_at')->nullable();
                $table->timestamps();
            }
        );//hospitalisation_payment
        Schema::create(
            AU_DB_PREFIX.'hosp_payment_detail',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('hosp_payment_id')->index();
                $table->text('content');
            }
        );//hospitalisation_payment_detail
        Schema::create(
            AU_DB_PREFIX.'payment_assurance',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('payment_id')->index();
                $table->string('id_cart');
                $table->string('agency')->comment('trance vie, cmu ...');
                $table->integer('percentage');
            }
        );//hospitalisation_payment_with_assurance
        //appointment
        Schema::create(
            AU_DB_PREFIX.'appointment',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('patient_id');
                $table->integer('doctor_id');
                $table->integer('hospital_id');
                $table->string('title',100)->nullable();
                $table->text('comment');
                $table->date('date');
                $table->time('time');
                $table->tinyInteger('status')->default(0)->comment('0:new, 1:done,2:cancel');
                $table->timestamps();
            }
        );//appointment doctor_patient
        //doctor hopital
        Schema::create(
            AU_DB_PREFIX.'hospital_doctor',
            function (Blueprint $table) {
                $table->integer('hospital_id');
                $table->integer('doctor_id');
                $table->primary(['hospital_id','doctor_id']);
            }
        );//doctor hopital
        //doctor patient
        Schema::create(
            AU_DB_PREFIX.'patient_doctor',
            function (Blueprint $table) {
                $table->integer('patient_id');
                $table->integer('doctor_id');
                $table->primary(['patient_id','doctor_id']);
            }
        );//doctor hopital
        //consultation patient
        Schema::create(
            AU_DB_PREFIX.'consultation',
            function (Blueprint $table) {
                $table->id();
                $table->integer('patient_id');
                $table->integer('doctor_id');
                $table->integer('hospital_id');
                $table->string('health',200);
            }
        );//consultation
        //consultation patient detail
        Schema::create(
            AU_DB_PREFIX.'consultation_detail',
            function (Blueprint $table) {
                $table->id();
                $table->integer('consultation_id');
                $table->text('rapport');
            }
        );//consultation detail
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(AU_DB_PREFIX . 'partner_user');
        Schema::dropIfExists(AU_DB_PREFIX . 'partner_user_folder');
        Schema::dropIfExists(AU_DB_PREFIX . 'user_password_history');
        Schema::dropIfExists(AU_DB_PREFIX . 'partner_role');
        Schema::dropIfExists(AU_DB_PREFIX . 'partner_task');
        Schema::dropIfExists(AU_DB_PREFIX . 'partner_permission');
        Schema::dropIfExists(AU_DB_PREFIX . 'partner_user_permission');
        Schema::dropIfExists(AU_DB_PREFIX . 'partner_template');
        Schema::dropIfExists(AU_DB_PREFIX . 'partner_role_user');
        Schema::dropIfExists(AU_DB_PREFIX . 'partner_role_permission');
        Schema::dropIfExists(AU_DB_PREFIX . 'partner_log');
        Schema::dropIfExists(AU_DB_PREFIX . 'partner_user_chat');
        Schema::dropIfExists(AU_DB_PREFIX . 'patient_detail' );//detail
        Schema::dropIfExists(AU_DB_PREFIX . 'patient_allergy');//allergy
        Schema::dropIfExists(AU_DB_PREFIX . 'patient_pathology');//pathologie
        Schema::dropIfExists(AU_DB_PREFIX . 'subscribe');//souscription
        Schema::dropIfExists(AU_DB_PREFIX . 'war_hours');//doctor emploit du temps
        Schema::dropIfExists(AU_DB_PREFIX . 'doctor_speciality');//specialité
        //hospital
        Schema::dropIfExists(AU_DB_PREFIX.'hospital');
        Schema::dropIfExists(AU_DB_PREFIX.'hospital_room');//room
        Schema::dropIfExists(AU_DB_PREFIX.'doctor_patient');//room
        Schema::dropIfExists(AU_DB_PREFIX.'hospital_assurance');//room
        Schema::dropIfExists(AU_DB_PREFIX.'hospital_detail');//detail
        Schema::dropIfExists(AU_DB_PREFIX.'hospital_patient');//patient
        Schema::dropIfExists(AU_DB_PREFIX.'hospital_department');//service
        //hospitalisation
        Schema::dropIfExists(AU_DB_PREFIX.'hospital_hospitalisation');//hospitalisation
        Schema::dropIfExists(AU_DB_PREFIX.'hospitalisation_track');//hospitalisation_fiche
        Schema::dropIfExists(AU_DB_PREFIX.'hosp_prescription');//hospitalisation_prescription
        Schema::dropIfExists(AU_DB_PREFIX.'hosp_prescription_detail');//hospitalisation_prescription_detail
        Schema::dropIfExists(AU_DB_PREFIX.'hosp_analyse');//hospitalisation_analyse
        Schema::dropIfExists(AU_DB_PREFIX.'hosp_analyse_detail');//hospitalisation_analyse_detail
        Schema::dropIfExists(AU_DB_PREFIX.'hosp_payment');//hospitalisation_payment
        Schema::dropIfExists(AU_DB_PREFIX.'hosp_payment_detail');//hospitalisation_payment_detail
        //appointment
        Schema::dropIfExists(AU_DB_PREFIX.'appointment' );//appointment doctor_patient
        Schema::dropIfExists(AU_DB_PREFIX.'payment_assurance' );//appointment doctor_patient
        //doctor hopital
        Schema::dropIfExists(AU_DB_PREFIX.'hospital_doctor');//doctor hopital
        Schema::dropIfExists(AU_DB_PREFIX.'consultation');//doctor hopital
        Schema::dropIfExists(AU_DB_PREFIX.'consultation_detail');//doctor hopital
        //doctor patient
        Schema::dropIfExists(AU_DB_PREFIX.'patient_doctor');//doctor hopital
        Schema::dropIfExists(AU_DB_PREFIX.'patient');
        Schema::dropIfExists(AU_DB_PREFIX.'patient_address');
        Schema::dropIfExists(AU_DB_PREFIX.'partner_employer');
        Schema::dropIfExists(AU_DB_PREFIX . 'menu_partner');

    }
}
