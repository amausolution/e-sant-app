<?php

use App\Partner\Controllers\ConsultationController;
use App\Partner\Controllers\MedicineBrandController;
use App\Partner\Controllers\MedicineCategoryController;
use App\Partner\Controllers\PharmacyProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Route plugin
Route::group(
    [
        'middleware' => AU_PARTNER_MIDDLEWARE,

    ],
    function () {
        foreach (glob(app_path() . '/Plugins/*/*/Partner/Route.php') as $filename) {
            require_once $filename;
        }

    }

);
Route::group(['prefix' => AU_PARTNER_PREFIX,'middleware' => AU_PARTNER_MIDDLEWARE],function (){
    Route::get('/select_department',function (){
        return Inertia::render('SelectDepart',[
            'departments'=>\Partner::user()->areDepartments
        ]);
    })->name('department');
    //check department
    Route::post('/check-department', [ConsultationController::class,'postDepart'])->name('check.department');
});
Route::group(['prefix' => AU_PARTNER_PREFIX,'middleware'=>AU_PARTNER_MIDDLEWARE],function (){
    Route::group(['prefix' => 'cash_desk'],  function () {
        Route::get('/', \App\Http\Livewire\Partner\Ticket\CheckoutPatient::class)->name('ticket.index');
        Route::get('/patient_new', \App\Http\Livewire\Partner\Ticket\NewPatient::class)->name('create.new');
    });
});

Route::group(['prefix' => AU_PARTNER_PREFIX,'middleware' => AU_PARTNER_MIDDLEWARE],function (){
    Route::group(['prefix' => 'consultation'],  function () {
        Route::get('/', [ConsultationController::class,'index'])->name('consultation.index')->middleware('check.depart');
        Route::get('/patient/{id}', [ConsultationController::class,'edit'])->name('consultation.edit');
        Route::get('/profile_patient/{id}', [ConsultationController::class,'profile'])->name('patient.profile');
        Route::put('/doctor/store',[ConsultationController::class,'store'])->name('consultation.store');

        /*        Route::post('/post_patient_first_diag',[ ConsultationController::class,'postFD'] )->name('consultation.fd');
                Route::post('/post_diag',[ ConsultationController::class,'postDiag'] )->name('post.diag');
                Route::post('/post_prescription',[ ConsultationController::class,'postPrescription'] )->name('post.prescription');
                Route::put('/end_consultation',[ ConsultationController::class,'endConsultation'] )->name('end.cons');*/
        Route::post('/Analyse',[ConsultationController::class,'addAnalysis'])->name('Analyse.add');
        Route::post('/hospitalized_patient',[ConsultationController::class,'addHospitalisation'])->name('hospitalisation.add');
    });

    //live consultation

    Route::group(['prefix'=> 'live_consultation'], function (){
        Route::get('/',[\App\Partner\Controllers\LiveConsultationController::class,'index'])->name('live_consultation.index');
    });
    //Patients partner
    Route::group(['prefix'=> 'patients'], function (){
        Route::get('/',[\App\Partner\Controllers\PatientController::class,'index'])->name('patients.index');
        Route::get('/show/{id}',[\App\Partner\Controllers\PatientController::class,'show'])->name('patients.show');
        Route::get('/consultation/show/{id}',[\App\Partner\Controllers\PatientController::class,'showConsultation'])->name('patient.consultation.show');
        Route::post('/pathology',[\App\Partner\Controllers\PatientController::class,'postPathology'])->name('patient.post.pathology');
        Route::post('/allergy',[\App\Partner\Controllers\PatientController::class,'postAllergy'])->name('patient.post.allergy');
    });

    //register patient

    Route::group(['prefix'=> 'registers'], function (){
        Route::get('/',[\App\Partner\Controllers\RegisterPatientController::class,'index'])->name('register.index');
        Route::get('/create',[\App\Partner\Controllers\RegisterPatientController::class,'create'])->name('register.create');
        Route::get('/show/{id}',[\App\Partner\Controllers\RegisterPatientController::class,'show'])->name('register.show');
        Route::get('/edit/{id}',[\App\Partner\Controllers\RegisterPatientController::class,'edit'])->name('register.show');
        Route::post('/store',[\App\Partner\Controllers\RegisterPatientController::class,'store'])->name('register.store');
        Route::put('/update/{id}',[\App\Partner\Controllers\RegisterPatientController::class,'update'])->name('register.update');
    });


    Route::group(['prefix'=> 'doctor'], function (){
        Route::get('/',[\App\Partner\Controllers\DoctorController::class,'index'])->name('doctor.index');
        Route::get('/create',[\App\Partner\Controllers\DoctorController::class,'create'])->name('doctor.create');
        Route::post('/store',[\App\Partner\Controllers\DoctorController::class,'store'])->name('doctor.store');
        Route::get('/edit/{id}',[\App\Partner\Controllers\DoctorController::class,'edit'])->name('doctor.edit');
        Route::get('/doctor/{id}',[\App\Partner\Controllers\DoctorController::class,'show'])->name('doctor.show');
        Route::put('/update/{id}',[\App\Partner\Controllers\DoctorController::class,'update'])->name('doctor.update');
        Route::put('/reset_password/{id}',[\App\Partner\Controllers\DoctorController::class,'resetPW'])->name('doctor.pw.reset');
        Route::delete('/destroy',[\App\Partner\Controllers\DoctorController::class,'delete'])->name('doctor.destroy');
    });
    //room and bed for hospitalization
    Route::group(['prefix'=> 'config'], function (){
        Route::get('/rooms',[\App\Partner\Controllers\SettingController::class,'room'])->name('room.index');
        Route::get('/rooms/create',[\App\Partner\Controllers\SettingController::class,'roomCreate'])->name('room.create');
        Route::get('/rooms/edit/{id}',[\App\Partner\Controllers\SettingController::class,'roomEdit'])->name('room.edit');
        Route::put('/rooms/update/{id}',[\App\Partner\Controllers\SettingController::class,'roomUpdate'])->name('room.update');
        Route::post('/rooms/store',[\App\Partner\Controllers\SettingController::class,'roomStore'])->name('room.store');
        Route::delete('/rooms/destroy',[\App\Partner\Controllers\SettingController::class,'roomDestroy'])->name('room.destroy');
        Route::get('/setting',[\App\Partner\Controllers\SettingController::class,'setting'])->name('setting');
    });

    //room for bloc operator

    Route::group(['prefix'=> 'bloc'], function (){
        Route::get('/',[\App\Partner\Controllers\BlocController::class,'index'])->name('bloc.index');
        Route::put('/update/{id}',[\App\Partner\Controllers\BlocController::class,'update'])->name('bloc.update');
        Route::post('/store',[\App\Partner\Controllers\BlocController::class,'store'])->name('bloc.store');
        Route::delete('/destroy',[\App\Partner\Controllers\BlocController::class,'delete'])->name('bloc.destroy');
    });

    //route opération chirurgicale
    Route::group(['prefix'=> 'operation'], function (){
        Route::get('/',[\App\Partner\Controllers\OperationController::class,'index'])->name('operation.index');
        Route::get('create/',[\App\Partner\Controllers\OperationController::class,'create'])->name('operation.create');
        Route::post('/check_matricule',[\App\Partner\Controllers\OperationController::class,'check'])->name('operation.check');
        Route::get('edit/{id}',[\App\Partner\Controllers\OperationController::class,'edit'])->name('operation.edit');
        Route::put('/update/{id}',[\App\Partner\Controllers\OperationController::class,'update'])->name('operation.update');
        Route::post('/store',[\App\Partner\Controllers\OperationController::class,'store'])->name('operation.store');
        Route::post('/store/task',[\App\Partner\Controllers\OperationController::class,'task'])->name('operation.task');
        Route::put('/update/task/{id}',[\App\Partner\Controllers\OperationController::class,'done'])->name('task.done');
        Route::put('/sort/task/{id}',[\App\Partner\Controllers\OperationController::class,'sortTask'])->name('task.sort');
        Route::delete('/delete/task/{id}',[\App\Partner\Controllers\OperationController::class,'deleteTask'])->name('task.delete');
        Route::delete('/destroy',[\App\Partner\Controllers\OperationController::class,'delete'])->name('operation.destroy');

    });

    Route::group(['prefix'=> 'hospitalisation'], function (){
        Route::get('/',[\App\Partner\Controllers\HospitalisationController::class,'index'])->name('hospitalisation.index');
        Route::get('/{id}/consultation',[\App\Partner\Controllers\HospitalisationController::class,'consultation'])->name('hospitalisation.consultation');
        Route::post('/{id}/consultation',[\App\Partner\Controllers\HospitalisationController::class,'postConsultation'])->name('hospitalisation.PostConsultation');
        Route::get('/edit/{id}',[\App\Partner\Controllers\HospitalisationController::class,'edit'])->name('hospitalisation.edit');
        Route::get('/hospitalized/{id}',[\App\Partner\Controllers\HospitalisationController::class,'editHospitalize'])->name('hospitalisation.hospitalized.edit');
        Route::get('/hospitalized',[\App\Partner\Controllers\HospitalisationController::class,'hospitalized'])->name('hospitalized.index');
        Route::get('/transfer/patient/{id}',[\App\Partner\Controllers\HospitalisationController::class,'transfer'])->name('hospitalized.transfer');
        Route::post('/hospitalized/patient',[\App\Partner\Controllers\HospitalisationController::class,'storeHospitalisation'])->name('hospitalisation.store');
        Route::get('/patient/hospitalized/{id}',[\App\Partner\Controllers\HospitalisationController::class,'endHospitalized'])->name('hospitalized.end');
        Route::post('/hospitalisation/destroy',[\App\Partner\Controllers\HospitalisationController::class,'destroy'])->name('hospitalisation.destroy');
    });
    Route::group(['prefix'=> 'laboratory'], function (){
        Route::get('/analyses',[\App\Partner\Controllers\AnalyseController::class,'index'])->name('analyse.index');
        Route::get('/analyse/{id}',[\App\Partner\Controllers\AnalyseController::class,'edit'])->name('analyse.edit');
        Route::get('/analyse/show/{id}',[\App\Partner\Controllers\AnalyseController::class,'show'])->name('analyse.show');
        Route::delete('/analyse/delete',[\App\Partner\Controllers\AnalyseController::class,'delete'])->name('analyse.delete');
        Route::get('/settings',[\App\Partner\Controllers\AnalyseController::class,'lab'])->name('lab.setting');
        Route::get('/settings/lab/edit/{id}',[\App\Partner\Controllers\AnalyseController::class,'editLabSetting'])->name('lab.edit');
        Route::put('/settings/lab/update',[\App\Partner\Controllers\AnalyseController::class,'update'])->name('lab.update');
        Route::post('/analyse',[\App\Partner\Controllers\AnalyseController::class,'store'])->name('analyse.store');
        Route::post('/analyse/result',[\App\Partner\Controllers\AnalyseController::class,'result'])->name('analyse.result');
    });
    Route::group(['prefix'=> 'plugins'], function (){
        Route::get('/',[\App\Partner\Controllers\PartnerConfigController::class,'plugin'])->name('plugin.index');
        Route::post('/install',[\App\Partner\Controllers\PartnerConfigController::class,'install'])->name('plugin.install');
        Route::post('/uninstall',[\App\Partner\Controllers\PartnerConfigController::class,'uninstall'])->name('plugin.uninstall');
        Route::post('/delete',[\App\Partner\Controllers\PartnerConfigController::class,'uninstall'])->name('plugin.delete');
        Route::post('/disable',[\App\Partner\Controllers\PartnerConfigController::class,'disable'])->name('plugin.disable');
        Route::post('/enable',[\App\Partner\Controllers\PartnerConfigController::class,'enable'])->name('plugin.enable');
    });
    Route::group(['prefix'=> 'live_cycle'], function (){
        Route::get('/',[\App\Partner\Controllers\LiveCycleController::class,'index'])->name('live.index');
        Route::get('/death/patient/{id}',[\App\Partner\Controllers\LiveCycleController::class,'deathPatient'])->name('patient.death');
        Route::get('/death',[\App\Partner\Controllers\LiveCycleController::class,'death'])->name('live.death');
        Route::post('/post/death',[\App\Partner\Controllers\LiveCycleController::class,'storeDeath'])->name('live.death.store');
        Route::post('/post/born',[\App\Partner\Controllers\LiveCycleController::class,'storeBorn'])->name('live.born.store');
        Route::get('/born',[\App\Partner\Controllers\LiveCycleController::class,'born'])->name('live.born');
    });

    //ambulance route

    Route::group(['prefix'=> 'ambulance'], function (){
        Route::get('/',[\App\Partner\Controllers\AmbulanceController::class,'index'])->name('ambulance.index');
        Route::get('/create',[\App\Partner\Controllers\AmbulanceController::class,'create'])->name('ambulance.create');
        Route::get('/edit/{id}',[\App\Partner\Controllers\AmbulanceController::class,'edit'])->name('ambulance.edit');
        Route::put('/update/{id}',[\App\Partner\Controllers\AmbulanceController::class,'update'])->name('ambulance.update');
        Route::put('/status/update/{id}',[\App\Partner\Controllers\AmbulanceController::class,'updateStatus'])->name('ambulance.update.status');
        Route::delete('/destroy/{id}',[\App\Partner\Controllers\AmbulanceController::class,'destroy'])->name('ambulance.destroy');
        Route::post('/store',[\App\Partner\Controllers\AmbulanceController::class,'store'])->name('ambulance.store');
    });

    Route::group(['prefix'=> 'settings'], function (){
        Route::get('/partner',[\App\Partner\Controllers\PartnerConfigController::class,'index'])->name('setting.index');
        Route::get('/partner/config_global',[\App\Partner\Controllers\PartnerConfigController::class,'config'])->name('partner.config');
        Route::get('/partner/setting_email',[\App\Partner\Controllers\PartnerConfigController::class,'email'])->name('setting.email');
        Route::get('/partner/setting_localisation',[\App\Partner\Controllers\PartnerConfigController::class,'localisation'])->name('setting.localisation');
        Route::get('/partner/setting_invoice',[\App\Partner\Controllers\PartnerConfigController::class,'invoice'])->name('setting.invoice');
        Route::get('/partner/setting_salary',[\App\Partner\Controllers\PartnerConfigController::class,'salary'])->name('setting.salary');
        Route::get('/partner/setting_equipment',[\App\Partner\Controllers\PartnerConfigController::class,'equipment'])->name('setting.equipment');
        Route::post('/partner',[\App\Partner\Controllers\PartnerConfigController::class,'updateConfig'])->name('update.config');
        Route::put('/partner/update_info',[\App\Partner\Controllers\PartnerConfigController::class,'update'])->name('partner.update');
    });

    Route::group(['prefix'=> 'pharmacy'], function (){
        Route::get('/',[\App\Partner\Controllers\PharmacyController::class,'index'])->name('pharmacy.index');
        Route::put('/update/info',[\App\Partner\Controllers\PharmacyController::class,'updateInfo'])->name('pharmacy.updateInfo');
        //product
        Route::get('/medicines',[PharmacyProductController::class,'index'])->name('medicine.index');
        Route::get('/medicines/create',[PharmacyProductController::class,'create'])->name('medicine.create');
        Route::post('/medicines/store',[PharmacyProductController::class,'store'])->name('medicine.store');
        Route::get('/medicines/edit/{id}',[PharmacyProductController::class,'edit'])->name('medicine.edit');
        Route::get('/medicines/update/{id}',[PharmacyProductController::class,'update'])->name('medicine.update');
        Route::delete('/medicines/destroy',[PharmacyProductController::class,'destroy'])->name('medicine.destroy');

        //categories pharmacy
        Route::get('/categories',[MedicineCategoryController::class,'index'])->name('category.index');
        Route::get('/categories/create',[MedicineCategoryController::class,'create'])->name('category.create');
        Route::post('/categories/store',[MedicineCategoryController::class,'store'])->name('category.store');
        Route::get('/categories/edit/{id}',[MedicineCategoryController::class,'edit'])->name('category.edit');
        Route::get('/categories/update/{id}',[MedicineCategoryController::class,'update'])->name('category.update');
        Route::delete('/categories/destroy',[MedicineCategoryController::class,'destroy'])->name('category.destroy');

        //brand pharmacy
        Route::get('/brande',[MedicineBrandController::class,'index'])->name('brand.index');
        Route::get('/brande/create',[MedicineBrandController::class,'create'])->name('brande.create');
        Route::post('/brande/store',[MedicineBrandController::class,'store'])->name('brande.store');
        Route::get('/brande/edit/{id}',[MedicineBrandController::class,'edit'])->name('brande.edit');
        Route::get('/brande/update/{id}',[MedicineBrandController::class,'update'])->name('brande.update');
        Route::delete('/brande/destroy',[MedicineBrandController::class,'destroy'])->name('brande.destroy');
  });

    //live consultation

    Route::group(['prefix'=> 'equipment'], function (){
        Route::get('/',[\App\Partner\Controllers\EquipmentController::class,'index'])->name('equipment.index');
    });
    //Blood Bank Route
    Route::group(['prefix'=> 'blood_bank'], function (){
        Route::get('/',[\App\Partner\Controllers\BloodBankController::class,'index'])->name('blood_bank.index');
        Route::get('/create',[\App\Partner\Controllers\BloodBankController::class,'create'])->name('blood_bank.create');
    });
});




Route::group(
    [
        'prefix' => AU_PARTNER_PREFIX,
        'middleware' => AU_PARTNER_MIDDLEWARE,
        'namespace' => 'App\Partner\Controllers'
    ],
    function () {
        foreach (glob(__DIR__ . '/Routes/*.php') as $filename) {
            require_once $filename;
        }
        Route::get('/', 'DashboardPartnerController@index')->name('partner.home');
        Route::get('deny', 'DashboardPartnerController@deny')->name('partner.deny');
        Route::get('data_not_found', 'DashboardPartnerController@dataNotFound')->name('partner.data_not_found');
        Route::get('deny_single', 'DashboardPartnerController@denySingle')->name('partner.deny_single');

        //Language
        Route::get('locale/{code}', function ($code) {
            session(['locale' => $code]);
            return back();
        })->name('partner.locale');

        //theme
        Route::get('theme/{theme}', function ($theme) {
            session(['partnerTheme' => $theme]);
            if (!\Partner::user()->isViewAll()) {
                \Partner::user()->update(['theme' => $theme]);
            }
            return back();
        })->name('partner.theme');

        Route::post('/partner/upload', function (\Illuminate\Http\Request $request){
            $consultation = new \Feggu\Core\Partner\Models\FegguConsultation();
            $consultation->id = 0;
            $consultation->exists = true;
            $image=$consultation->addMediaFromRequest('upload')->toMediaCollection('images');

            return response()->json([
                'url'=>$image->getUrl()
            ]);
        })->name('partner_upload');
    }
);
