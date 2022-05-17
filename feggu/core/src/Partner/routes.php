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
//        Route::post('/delete', 'ConsultationController@delete')->name('consultation_patient.delete');
//        Route::get('/show/{slug}', 'ConsultationController@show')->name('consultation_patient.show');
//        Route::post('/diagnostic/{id}', 'ConsultationController@updateDiag')->name('diagnostic.edit');
//        Route::post('/doctor/result/update', 'ConsultationController@updateHealth')->name('doctor.result');
//        Route::post('/doctor/add/prescription', 'ConsultationController@addPrescription')->name('doctor.add_prescription');
//        Route::post('/delete/diagnostic', 'ConsultationController@deleteDiag')->name('diagnostic.delete');
//        Route::post('/doctor/add/analysis', 'ConsultationController@addAnalysis')->name('analyse.store');
//        Route::post('/doctor/add/pathology', 'ConsultationController@addPathology')->name('pathology.store');
//        Route::post('/doctor/add/allergy', 'ConsultationController@addAllergy')->name('allergy.store');
//        Route::post('/doctor/consultation/hospitalized', 'ConsultationController@addHospitalisation')->name('hospitalisation.store');
//
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
        Route::post('/update/{id}',[\App\Partner\Controllers\DoctorController::class,'update'])->name('doctor.update');
        Route::put('/reset_password/{id}',[\App\Partner\Controllers\DoctorController::class,'resetPW'])->name('doctor.pw.reset');
        Route::post('/destroy',[\App\Partner\Controllers\DoctorController::class,'delete'])->name('doctor.destroy');
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
