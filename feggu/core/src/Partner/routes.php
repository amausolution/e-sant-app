<?php

use App\Partner\Controllers\ConsultationController;
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
        Route::get('/patient/{slug}', [ConsultationController::class,'edit'])->name('consultation.edit');
        Route::get('/profile_patient/{id}', [ConsultationController::class,'profile'])->name('patient.profile');
        Route::post('/post_patient_first_diag',[ ConsultationController::class,'postFD'] )->name('consultation.fd');
        Route::post('/post_diag',[ ConsultationController::class,'postDiag'] )->name('post.diag');
        Route::post('/post_prescription',[ ConsultationController::class,'postPrescription'] )->name('post.prescription');
        Route::put('/end_consultation',[ ConsultationController::class,'endConsultation'] )->name('end.cons');
        Route::post('/analyse',[ConsultationController::class,'addAnalysis'])->name('analyse.add');
        Route::post('/hospitalized_patient',[ConsultationController::class,'addHospitalisation'])->name('hospitalisation.add');
    });
    Route::group(['prefix'=> 'patients'], function (){
       Route::post('/pathology',[\App\Partner\Controllers\PatientController::class,'postPathology'])->name('patient.post.pathology');
       Route::post('/allergy',[\App\Partner\Controllers\PatientController::class,'postAllergy'])->name('patient.post.allergy');
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
    Route::group(['prefix'=> 'config'], function (){
        Route::get('/rooms',[\App\Partner\Controllers\SettingController::class,'room'])->name('room.index');
        Route::get('/rooms/create',[\App\Partner\Controllers\SettingController::class,'roomCreate'])->name('room.create');
        Route::get('/rooms/edit/{id}',[\App\Partner\Controllers\SettingController::class,'roomEdit'])->name('room.edit');
        Route::put('/rooms/update/{id}',[\App\Partner\Controllers\SettingController::class,'roomUpdate'])->name('room.update');
        Route::post('/rooms/store',[\App\Partner\Controllers\SettingController::class,'roomStore'])->name('room.store');
        Route::delete('/rooms/destroy',[\App\Partner\Controllers\SettingController::class,'roomDestroy'])->name('room.destroy');

        Route::get('/setting',[\App\Partner\Controllers\SettingController::class,'setting'])->name('setting');
    });
    Route::group(['prefix'=> 'hospitalisation'], function (){
        Route::get('/',[\App\Partner\Controllers\HospitalisationController::class,'index'])->name('hospitalisation.index');
        Route::get('/edit/{id}',[\App\Partner\Controllers\HospitalisationController::class,'edit'])->name('hospitalisation.edit');
        Route::get('/hospitalized/{id}',[\App\Partner\Controllers\HospitalisationController::class,'editHospitalize'])->name('hospitalisation.hospitalized.edit');
        Route::get('/hospitalized',[\App\Partner\Controllers\HospitalisationController::class,'hospitalized'])->name('hospitalized.index');
        Route::post('/hospitalized/patient',[\App\Partner\Controllers\HospitalisationController::class,'storeHospitalisation'])->name('hospitalisation.store');
      //  Route::post('/hospitalized/patient',[\App\Partner\Controllers\HospitalisationController::class,'setting'])->name('setting');
        Route::post('/hospitalisation/destroy',[\App\Partner\Controllers\HospitalisationController::class,'destroy'])->name('hospitalisation.destroy');
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
