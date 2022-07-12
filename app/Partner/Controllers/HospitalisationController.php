<?php

namespace App\Partner\Controllers;


use App\Http\Controllers\RootPartnerController;

use App\Partner\Models\Pharmacy;
use Carbon\Carbon;
use Feggu\Core\Partner\Models\CategoryAnalysisDetail;
use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguConsultationAnalyse;
use Feggu\Core\Partner\Models\FegguConsultationPrescription;
use Feggu\Core\Partner\Models\FegguHospitalisation;

use Feggu\Core\Partner\Models\HospitalisationTrack;
use Feggu\Core\Partner\Models\HospitalRoom;
use Feggu\Core\Partner\Models\HospitalRoomBet;

use Feggu\Core\Partner\Partner;

use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Response;

use Illuminate\Support\Facades\Request;

use Illuminate\Support\Str;
use Inertia\Inertia;
use Validator;

class HospitalisationController extends RootPartnerController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $partnerId = session('partnerId');
        if (!$partnerId){
            abort(404);
        }

        return Inertia::render('Partner/Hospitalisation/Index',[
            'title'=>__('List Ask Hospitalisation'),
            'hospitalisations'=> FegguHospitalisation::where('status',0)->where('hospital_id',session('partnerId'))
                ->filter(Request::only('first_name','identifier','last_name'))
                ->paginate(20)
                ->withQueryString()
                ->through(fn ($hp) => [
                    'id' => $hp->slug,
                    'phone' => $hp->patient->mobil,
                    'name' => $hp->patient->name,
                    'phone_urgency' => $hp->patient->phone_urgency,
                    'patientId'=>$hp->patient->doc_number,
                    'doctor'=>$hp->doctor->name,
                    'gender'=> gender()[$hp->patient->gender],
                    'avatar'=> asset($hp->patient->getAvatar()),
                ]),
            'filters' => \request()->all('first_name','identifier','last_name'),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function hospitalized()
    {
        $partnerId = session('partnerId');
        if (!$partnerId){
            abort(404);
        }

        return Inertia::render('Partner/Hospitalisation/Patient',[
            'title'=>__('List Ask Hospitalisation'),
            'hospitalisations'=> FegguHospitalisation::where('status',1)->where('hospital_id',session('partnerId'))->with('patient')
                ->orderByName()
                ->filter(Request::only('first_name','identifier','last_name','accompanying'))
                ->paginate(20)
                ->withQueryString()
                ->through(fn ($hp) => [
                    'id' => $hp->id,
                    'name' => $hp->patient->name,
                    'phone' => $hp->patient->mobil,
                    'accompanying' => $hp->accompanying,
                    'phone_urgency' => $hp->patient->phone_urgency,
                    'patientId'=>$hp->patient->doc_number,
                    'doctor'=>$hp->doctor->name,
                    'gender'=> gender()[$hp->patient->gender],
                    'avatar'=> asset($hp->patient->getAvatar()),
                ]),
            'filters' => \request()->all('first_name','identifier','last_name','accompanying'),
        ]);
    }

    public function patient()
    {
        $partnerId = session('partnerId');
        if (!$partnerId){
            abort(404);
        }
        return view($this->templatePathPartner.'hospitalisation.index',[
            'title'=>__('List Patients Hospitalize'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'hospitalisation'=>1,
            'title_form' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . __('List Hospitalisation'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $slug
     * @return string
     */
    public function edit($slug)
    {
        $hospitalisation = FegguHospitalisation::where('slug',$slug)->firstOrFail();
        if (!$hospitalisation){
            return 'no data';
        }

        return Inertia::render('Partner/Hospitalisation/New',[
            'title'=>__('Detail Hospitalisation'),
            'hospitalisation'=>$hospitalisation,
            'rooms'=>HospitalRoom::with('beds')->get(),
            'type_piece'=>typePiece()
        ]);
    }

    public function editHospitalize($id)
    {
        //dd($slug);
        $hospitalisation = FegguHospitalisation::where('id',$id)->where('status',1)->firstOrFail();
        if (!$hospitalisation){
            return 'no data';
        }

        return Inertia::render('Partner/Hospitalisation/Hospitalized',[
            'title'=>__('Detail Hospitalisation'),
            'hospitalisation'=>[
                'id'=>$hospitalisation->id,
                'room'=>$hospitalisation->room,
                'bed'=>$hospitalisation->bed,
                'doctor'=>$hospitalisation->doctor->name,
                'indemnification'=>$hospitalisation->indemnification,
                'hospitalized_by'=>$hospitalisation->hospitalized->name,
                'date_in'=>$hospitalisation->date_in,
                'accompanying'=> [
                    'accompanying'=>$hospitalisation->accompanying,
                    'accompanying_phone'=>$hospitalisation->accompanying_phone,
                    'piece_guarantor'=>$hospitalisation->piece_guarantor,
                    'type_piece'=>typePiece()[$hospitalisation->type_piece],
                ],
                'consultation'=> $hospitalisation->consultations()->get()->map(function ($consultation){
                    return [
                        'doctor'=>$consultation->doctor->name,
                        'id'=>$consultation->id,
                        'date'=>showDateTime($consultation->created_at),
                        'diagnostic'=>au_html_render($consultation->diagnostic),
                        'first_diag'=>$consultation->first_diag??[],

                        'prescriptions'=> $consultation->prescriptions()->get()->map(function ($prescription){
                             return [
                               'id'=>$prescription->id,
                               'doctor'=>$prescription->doctor->name,
                               'label'=>$prescription->label,
                               'quantity'=>$prescription->quantity,
                               'dosage'=>$prescription->dosage,
                               'duration'=>$prescription->duration,
                             ];
                         }),
                        'analyses'=> $consultation->analyses()->get()->map(function ($analyse) {
                            return [
                                'title'=> json_decode($analyse->analyse,true)['title'],
                                'id'=> json_decode($analyse->analyse,true)['id'],
                                'emergency'=> $analyse->emergency,
                                'note'=>$analyse->note,
                                'status'=>$analyse->status,
                            ];
                        })
                            ];
                }),
                'patient'=>[
                    'blood'=>$hospitalisation->patient->blood_group,
                    'name'=>$hospitalisation->patient->name,
                    'gender'=>gender()[$hospitalisation->patient->gender],
                    'patientID'=>$hospitalisation->patient->doc_number,
                    'phone'=>$hospitalisation->patient->mobil,
                    'birthday'=> showDob($hospitalisation->patient->birthday),
                    'address'=>$hospitalisation->consultation->address,
                    'email'=>$hospitalisation->patient->email,
                    'age'=>$hospitalisation->consultation->age,
                    'avatar'=>asset($hospitalisation->patient->getAvatar())
                ]
            ],
            //'rooms'=>HospitalRoom::with('beds')->get(),
            'type_piece'=>typePiece()
        ]);
    }

    public function transfer($id)
    {
        $hospitalisation = FegguHospitalisation::find($id);

        if (!$hospitalisation){
            return 'no data';
        }

        return Inertia::render('Partner/Hospitalisation/Transfer',[
            'title'=> __('Transfer Patient'),
            'hospitalisation' => [
                'id'=>$hospitalisation->id,
            ]
        ]);
    }

    public function consultation($id)
    {
        $hospitalisation = FegguHospitalisation::find($id);

        if (!$hospitalisation){
            return 'no data';
        }
        return Inertia::render('Partner/Hospitalisation/Consultation',[
            'title'=> __('New Consultation'),
            'category_analyses'=>CategoryAnalysisDetail::where('status',1)->get()->map->only('id','title'),
            'hospitalisation' => [
                'id'=>$hospitalisation->id,
                'patient'=>$hospitalisation->patient->doc_number,

            ]
        ]);
    }

    public function postConsultation($id)
    {
        $data = Request::all();
        $hospitalisation = FegguHospitalisation::find($id);

        if (!$hospitalisation){
            return 'no data';
        }
      //  dd($data);

        Request::validate([
            'diagnostic'=>'required',
            ],[]);


        $firstDiag = [];
       if (isset($data['tension'])){
           $firstDiag['tension']= $data['tension'];
       }
        if (isset($data['temperature'])){
            $firstDiag['temperature']= $data['temperature'];
        }
        if (isset($data['sugar'])){
            $firstDiag['sugar']= $data['sugar'];
        }
        $dataConsultation = [
            'diagnostic'=>$data['diagnostic'],
            'result'=>$data['result'],
           // 'first_diag'=>$firstDiag,
            'hospital_id'=>session('partnerId'),
            'patient_id'=>$hospitalisation->patient_id,
            'doc_number'=>$hospitalisation->patient->doc_number,
            'slug'=>Str::uuid()->toString(),
            'ip_doctor'=>\request()->ip(),
            'doctor_user_agent'=>\request()->server('HTTP_USER_AGENT'),
            'view_at'=>Carbon::now()->tz('Africa/Dakar')->format('H:i:m'),
            'status'=>2,
            'age'=> showAge($hospitalisation->patient->birthday),
            'doctor_id'=>\Partner::user()->id,
        ];

       // $dataConsultation = au_clean($dataConsultation,[], true);
        $dataConsultation['first_diag']=$firstDiag;
        $consultation = FegguConsultation::create($dataConsultation);
        $prescriptions= $data['prescriptions']??[];
        $arrPrescript = [];
        foreach ($prescriptions as $key => $prescript) {
            if ($prescript) {
                $arrPrescript[] = new FegguConsultationPrescription([
                    'doctor_id'=> \Partner::user()->id,
                    'label' => $prescript['label'],
                    'quantity' => $prescript['quantity'],
                    'dosage' => $prescript['dosage'],
                    'dosage_text' => $prescript['dosageText'],
                    'duration' => $prescript['duration'],
                ]);
            }
        }
        $analyses=$data['analyses']??[];
        //  dd($analyses);
        $arrAnalyse = [];
        foreach ($analyses as $key => $analyse) {
            if ($analyse) {
                $arrAnalyse[] = new FegguConsultationAnalyse([
                    'Analyse'=>$analyse['analyse_id'],
                    'doctor_id'=>Partner::user()->id,
                    'note'=>$analyse['note']??null,
                    'emergency'=>$analyse['emergency'],
                ]);
            }
        }
        //dd($arrAnalyse);
        $consultation->analyses()->saveMany($arrAnalyse);
        //dd($dataConsultation,$arrPrescript);
        $consultation->prescriptions()->saveMany($arrPrescript);
        $hospitalisation->consultations()->attach($consultation);
        return redirect()->route('hospitalisation.hospitalized.edit',['id' =>$id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $slug
     * @return string
     */
    public function storeHospitalisation()
    {
        $data =Request::all();
       // dd($data);

        $hospitalization = FegguHospitalisation::where('slug',$data['slug'])->firstOrFail();
        if (!$hospitalization){
            return 'no data';
        }
        Request::validate([
            'accompanying' => 'required',
            'accompanying_phone'=>'required',
            'piece_guarantor'=>'required',
            'type_piece'=>'required',
            'bed'=>'required',
            'slug'=>'required'
        ],[

        ]);

        $data = au_clean($data);
        $bed = HospitalRoomBet::findOrFail($data['bed']);
        $dataUpdate= [
            'accompanying'=>$data['accompanying'],
            'accompanying_phone'=>$data['accompanying_phone'],
            'piece_guarantor'=>$data['piece_guarantor'],
            'indemnification'=>!empty($data['indemnification']) ? 1 : 0,
            'type_piece'=>$data['type_piece'],
            'room'=>$bed->room->room_number,
            'bed'=>$bed->bed_number,
            'bed_id'=>$bed->id,
            'price'=>$bed->room->price,
            'hospitalized_by'=> Partner::user()->id,
            'status'=>1,
            'date_in'=>date('y-m-d:h:i'),
        ];

        $hospitalization->update($dataUpdate);
        $bed->update(['status'=>1]);
        return redirect()->route('hospitalisation.index')->with('success', __('patient hospitalized successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function showHospitalized($slug)
    {
        $hospitalisation = FegguHospitalisation::where('slug',$slug)->firstOrFail();
        if (!$hospitalisation){
            return 'no data';
        }

        return view($this->templatePathPartner.'hospitalisation.show_hospitalized',[
            'title'=>__('See Detail Hospitalisation'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'hospitalisation'=>$hospitalisation,
          //  'analyses'=> CategoryAnalysis::with('specifications')
        ]);
    }
    public function endHospitalized($id)
    {

        $hospitalisation = FegguHospitalisation::find($id);
        if (!$hospitalisation){
            return 'no data';
        }

        return Inertia::render('Partner/Hospitalisation/End',[
            'title'=>__('Discharge Patient'),

            'hospitalisation'=> [
                'date'=>$hospitalisation->date_in,
                'price'=>$hospitalisation->price,
                'bed'=>$hospitalisation->bed,
                'room'=>$hospitalisation->room,
                'hospitalisation'=>$hospitalisation->id,
                'indemnification'=>$hospitalisation->indemnification,
                'insurer'=>[
                    'name'=>$hospitalisation->patient->insurer->insurer_name
                ],
                'accompanying'=>[
                    'phone'=>$hospitalisation->accompanying_phone,
                    'name'=>$hospitalisation->accompanying,
                    'piece'=>$hospitalisation->piece_guarantor
                ],
                'consultations'=>$hospitalisation->consultations()->get()->map(function ($consultation){
                    return [
                      'created_at'=>$consultation->created_at,
                      'identifier'  => $consultation->identifier,
                      'prescriptions'=>$consultation->prescriptions,
                       'analyses'=>$consultation->analyses()->where('status',1)->get()->map(function ($analyse){
                            return [
                                'price'=>$analyse->price,
                                'result'=>$analyse->results()->where('labo_id',getPartner()->laboratory->id)->get()->map(function ($res){
                                    return [
                                        $res
                                    ];
                                })
                            ];
                        }),
                      'prescriptions_detail'=>$consultation->prescriptions()->where('status',1)->where('pharmacy_id',getPartner()->pharmacy->id)->get()->map(function ($prescription){
                          return [
                              'status'=>$prescription->status,
                              'label'=>$prescription->label,
                              'quantity'=>$prescription->quantity,
                              'price'=>$prescription->price,
                              'status_payment'=>$prescription->status_payment,
                              'type_payment'=>$prescription->type_payment,
                              'detail'=>$prescription->detail()->get()->map->only(''),
                          ];
                      }),
                    ];
                })
            ],
        ]);
    }

    public function diagnostic()
    {
       // dd(request()->all());
        $reload = false;
        $message='';
        $data = request()->all();
      //  dd($data['Analyse']);
        $validation_rule=[
            'diagnostic'=>'required',
            'idH'=>'required',
            'prescription'         => 'sometimes|required|array',
            'Analyse.*'         => 'sometimes|required',
            'prescription.label.*'  => 'required_with:prescription',
            'prescription.quantity.*' => 'required_with:prescription|min:1',
            'prescription.dosage.*' => 'nullable|string|max:50',
            'prescription.dosage_text.*.value.*' => 'sometimes|required|array',
            'prescription.duration.*' => 'nullable|numeric',
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($data, $validation_rule, [
            'prescription.*.label.required'   =>  'All prescription label is required',
            'prescription.*.quantity'           =>  'All prescription quantity is required',
        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'error', 'message'=>$validator->errors()]);
        }
        $data = au_clean($data,[]);

        $hospitalisation = FegguHospitalisation::find($data['idH']);
        $dataCons = [
            'patient_id'=> $hospitalisation->patient_id,
            'doctor_id'=>Partner::user()->id,
            'hospital_id'=>getPartner()->id,
            'diagnostic'=>$data['diagnostic'],
            'result'=>$data['result'],
            'status'=>1,
            'view_at'=>date('y-m-d h:i:s'),
           // 'slug'=>Str::uuid()->toString(),
            'first_diag'=>$data['extra']??[],
        ];
        $consultation = FegguConsultation::create($dataCons);

            $consultation->hospitalisations()->attach($hospitalisation->id);

        $prescription = $data['prescription']??[];
        $dataInsert = [];
        foreach ($prescription as $group => $rowGroup) {
            if (count($rowGroup)) {
                foreach ($rowGroup['label'] as $key => $nameAtt) {
                    if ($nameAtt) {
                        $dataAtt = au_clean(['label' => $nameAtt,
                            'doctor_id'=>Partner::user()->id,
                            'quantity' => $rowGroup['quantity'][$key],
                            'dosage' => $rowGroup['dosage'][$key]??null,
                            'duration' => $rowGroup['duration'][$key]??null,
                            'dosage_text' => $rowGroup['dosage_text']??[]
                        ], []);
                        $dataInsert[]= new FegguConsultationPrescription($dataAtt);
                        // $arrDataAtt[] = new ShopProductAttribute($dataAtt);
                    }
                }
            }
        }
        $consultation->prescriptions()->saveMany($dataInsert);

        //Insert Analyse
        if (isset($data['Analyse']) && $data['Analyse']){
            foreach ($data['Analyse']as $code => $value) {
                $analyse = new FegguConsultationAnalyse();
                $analyse->consultation_id      = $consultation->id;
                $analyse->doctor_id            = Partner::user()->id;
                $analyse->type_id              = $value;
                $analyse->emergency            = 1;
                $analyse->save();
            }
        }

        $message = __('Prescriptions added success');
        $reload = false;
        return response()->json(['status'=>'success', 'message'=>$message, 'reload'=>$reload]);

    }
    public function rapport()
    {
        $data = request()->all();
        $validation_rule=[
            'rapport'=>'required',
            'idHosp'=>'required',
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($data, $validation_rule, [
            'rapport.required'   =>  'The rapport is required',
        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'error', 'message'=>$validator->errors()]);
        }
        $data = au_clean($data,[],true);
        $hospitalisation = FegguHospitalisation::find($data['idHosp']);
        if (!$hospitalisation){
            return response()->json(['status'=>'error', 'message'=>__('Data not found'),'reload'=>true]);
        }
        $dataRapport = [
            'hospitalisation_id'=>$hospitalisation->id,
            'doctor_id'=>Partner::user()->id,
            'fiche'=>$data['rapport'],
        ];

        HospitalisationTrack::create($dataRapport);
        $message = __('Rapport added success');
        $reload = false;
        return response()->json(['status'=>'success', 'message'=>$message, 'reload'=>$reload]);

    }
}
