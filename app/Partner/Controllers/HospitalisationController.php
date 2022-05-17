<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RootPartnerController;
use Feggu\Core\Partner\Models\CategoryAnalysis;
use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguConsultationAnalyse;
use Feggu\Core\Partner\Models\FegguConsultationPrescription;
use Feggu\Core\Partner\Models\FegguHospitalisation;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\HospitalisationTrack;
use Feggu\Core\Partner\Models\HospitalRoom;
use Feggu\Core\Partner\Models\HospitalRoomBet;
use Feggu\Core\Partner\Models\PartnerBed;
use Feggu\Core\Partner\Partner;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Str;
use Validator;

class HospitalisationController extends RootPartnerController
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $partnerId = session('partnerId');
        if (!$partnerId){
            abort(404);
        }

        return view($this->templatePathPartner.'hospitalisation.index',[
            'title'=>__('List Ask Hospitalisation'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'hospitalisation'=>0,
            'title_form' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . __('List Hospitalisation'),
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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return void
     */
    public function show($slug)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $slug
     * @return Application|Factory|View|Response
     */
    public function edit($slug)
    {
        $hospitalisation = FegguHospitalisation::where('slug',$slug)->firstOrFail();
        if (!$hospitalisation){
            return 'no data';
        }

        return view($this->templatePathPartner.'hospitalisation.hospitalisation',[
            'title'=>__('Detail Hospitalisation'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'hospitalisation'=>$hospitalisation,
            'rooms'=>HospitalRoom::with('beds')->get(),
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $slug
     * @return RedirectResponse|Response
     */
    public function update(Request $request, $slug)
    {

        $data = $request->all();

        $dataOrigine = $request->all();
        $hospitalization = FegguHospitalisation::where('slug',$slug)->firstOrFail();
        if (!$hospitalization){
            return 'no data';
        }
        $validator = Validator::make($dataOrigine,[
            'accompanying' => 'required',
            'phone'=>'required',
            'piece'=>'required',
            'type_piece'=>'required',
            'bed_id'=>'required',
        ],[

        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = au_clean($data);
        $bed = HospitalRoomBet::findOrFail($data['bed_id']);
        $dataUpdate= [
            'accompanying'=>$data['accompanying'],
            'accompany_phone'=>$data['phone'],
            'piece_guarantor'=>$data['piece'],
            'type_piece'=>$data['type_piece'],
            'room'=>$bed->room->room_number,
            'bed'=>$bed->bed_number,
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
