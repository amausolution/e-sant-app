<?php

namespace App\Partner\Controllers;


use App\Http\Controllers\RootPartnerController;

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
            'hospitalisations'=> FegguHospitalisation::where('status',0)->where('hospital_id',session('partnerId'))->with('patient')
                ->orderByName()
                ->filter(Request::only('search','patientID','gender','phone','cin'))
                ->paginate(20)
                ->withQueryString()
                ->through(fn ($hp) => [
                    'id' => $hp->slug,
                    'name' => $hp->patient->name,
                    'phone_urgency' => $hp->patient->phone_urgency,
                    'patientId'=>$hp->patient->doc_number,
                    'doctor'=>$hp->doctor->name,
                    'gender'=> gender()[$hp->patient->gender],
                    'avatar'=> asset($hp->patient->getAvatar()),
                ]),
            'filters' => \request()->all('search','patientID','gender','phone','cin'),
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
                ->filter(Request::only('search','patientID','gender','phone','cin'))
                ->paginate(20)
                ->withQueryString()
                ->through(fn ($hp) => [
                    'id' => $hp->slug,
                    'name' => $hp->patient->name,
                    'phone_urgency' => $hp->patient->phone_urgency,
                    'patientId'=>$hp->patient->doc_number,
                    'doctor'=>$hp->doctor->name,
                    'gender'=> gender()[$hp->patient->gender],
                    'avatar'=> asset($hp->patient->getAvatar()),
                ]),
            'filters' => \request()->all('search','patientID','gender','phone','cin'),
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

    public function editHospitalize($slug)
    {
        //dd($slug);
        $hospitalisation = FegguHospitalisation::where('slug',$slug)->where('status',1)->firstOrFail();
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
                'accompanying'=>$hospitalisation->accompanying,
                'accompanying_phone'=>$hospitalisation->accompanying_phone,
                'piece_guarantor'=>$hospitalisation->piece_guarantor,
                'hospitalized_by'=>$hospitalisation->hospitalized->name,
                'date_in'=>$hospitalisation->date_in,
                'consultation'=> $hospitalisation->consultations()->get()->map(function ($consultation){
                    return [
                        'doctor'=>$consultation->doctor->name,
                        'id'=>$consultation->id,
                        'date'=>showDateTime($consultation->created_at),
                        'diagnostic'=>$consultation->diagnostic,
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
    public function endHospitalized($slug)
    {
        $hospitalisation = FegguHospitalisation::where('slug',$slug)->firstOrFail();
        if (!$hospitalisation){
            return 'no data';
        }

        return view($this->templatePathPartner.'hospitalisation.discharge_patient',[
            'title'=>__('Discharge Patient'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'hospitalisation'=>$hospitalisation,
        ]);
    }

    public function diagnostic()
    {
       // dd(request()->all());
        $reload = false;
        $message='';
        $data = request()->all();
      //  dd($data['analyse']);
        $validation_rule=[
            'diagnostic'=>'required',
            'idH'=>'required',
            'prescription'         => 'sometimes|required|array',
            'analyse.*'         => 'sometimes|required',
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

        //Insert analyse
        if (isset($data['analyse']) && $data['analyse']){
            foreach ($data['analyse']as $code => $value) {
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
