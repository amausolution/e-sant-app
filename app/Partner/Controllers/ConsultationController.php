<?php


namespace App\Partner\Controllers;


use App\Http\Controllers\RootPartnerController;
use Carbon\Carbon;
use Feggu\Core\Partner\Models\CategoryAnalysisDetail;
use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguConsultationAnalyse;
use Feggu\Core\Partner\Models\FegguConsultationPrescription;
use Feggu\Core\Partner\Models\FegguHospitalisation;

use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\PartnerLab;
use Feggu\Core\Partner\Models\PatientAllergy;
use Feggu\Core\Partner\Models\PatientPathology;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Feggu\Core\Partner\Partner;
use Inertia\Inertia;

class ConsultationController extends RootPartnerController
{
    public $groupBlood;
    public $departments;
    public function __construct()
    {
        parent::__construct();
        $this->groupBlood = DB::table(AU_DB_PREFIX.'feggu_blood')->pluck('blood');
    }

    public function postDepart()
    {
        Validator::make([
            'depart'=>'required',
            'room'=>'nullable|numeric|min:1',
        ],[]);

        session(['departmentIds'=>\request()->depart]);
        session(['room'=>\request()->room]);
        $notify[] = ['success', 'Brand Created Successfully'];
        return redirect()->route('consultation.index')->withNotify($notify);
    }


    public function index()
    {
        $partnerId = session('partnerId');
        if (!$partnerId){
            abort(404);
        }
        return Inertia::render('Partner/Consultation/Index',[
            'filters' => \request()->all('search', 'trashed'),
            'consultations' => FegguConsultation::where('hospital_id',session('partnerId'))
                ->whereDate('created_at', '=', date('Y-m-d'))
                ->where('department_id',session('departmentIds'))
                ->filter(Request::only('search', 'trashed'))
                ->paginate(1)
                ->withQueryString()
                ->through(fn ($consult) => [
                    'id' => $consult->id,
                    'slug' => $consult->slug,
                    'phone_number' => $consult->phone_number,
                    'ticket' => $consult->ticket,
                    'status' => $consult->last_name,
                    'age'=>$consult->age,
                    'address'=>$consult->address,
                    'first_diag'=>$consult->first_diag ?? [],
                    'patient' => $consult->patient ? $consult->patient->only('name','doc_number') : null,
                ]),
              'title'=>__('List Patient')
        ]);
    }

    public function edit($slug)
    {

        $data = FegguConsultation::where('slug',$slug)->with(['patient'])->first();

        if (!$data){
            abort(404);
        }
        if ($data->status === 0){
            $dataUpdate = [
                'doctor_id'=> \Partner::user()->id,
                'ip_doctor'=>\request()->ip(),
                'doctor_user_agent'=>\request()->server('HTTP_USER_AGENT'),
                'view_at'=>Carbon::now()->tz('Africa/Dakar')->format('H:i:m'),
                'status'=>2
            ];
            $data->update($dataUpdate);
        }

        return Inertia::render('Partner/Consultation/Consult',[
            'title' => __('Patient Consultation'),
            'category_analyses'=>CategoryAnalysisDetail::join(AU_DB_PREFIX.'category_analysis', AU_DB_PREFIX.'category_analysis_detail.category_analysis_id', '=', AU_DB_PREFIX.'category_analysis.id')
                ->get([AU_DB_PREFIX.'category_analysis_detail.title', AU_DB_PREFIX.'category_analysis_detail.id'])->toArray(),
            'consultation' => [
                'slug'=>$data->slug,
                'status'=>$data->status,
                'ticket'=>$data->ticket,
                'patient_id'=>$data->patient_id,
                'department'=> showDepart($data->department_id),
                'first_diag'=>$data->first_diag??[],
                'age'=>$data->age,
                'diagnostic'=>$data->diagnostic??[],
                'gender'=>gender()[$data->patient->gender],
                'hospital'=>$data->hospital->getTitle(),
                'name'=>$data->patient->name,
                'blood_group'=>$data->patient->blood_group,
                'mobil'=>$data->patient->mobil,
                'doc_number'=>$data->patient->doc_number,
                'email'=>$data->patient->email,
                'phone2'=>$data->patient->phone2,
                'phone_emergency'=>$data->patient->phone_emergency,
                'birthday'=> showDate($data->patient->birthday),
                'godfather'=>$data->godfather,
                'avatar'=>$data->patient->getAvatar(),
                'analyses'=>$data->analyses,
                'prescriptions'=>$data->prescriptions??[],
                'hospitalisation'=>$data->hospitalisation??[],
                'allergies'=>$data->patient->allergies,
                'pathologies'=>$data->patient->pathologies,
                'room'=>session('room'),
                'partner_analyses'=> PartnerLab::where('partner_id', session('partnerId'))->with('categories')->first()->toArray(),
            ],
        ]);
    }

    public function postFD()
    {
        $data = \request()->all();
        $consultation = FegguConsultation::where('slug',$data['idc'])->first();
        if (!$consultation){
            return 'no data';
        }
        $dataUpdate = [];
        if (isset($data['tension'])){
            $dataUpdate['tension']= $data['tension'];
        }
        if (isset($data['temperature'])){
            $dataUpdate['temperature']= $data['temperature'];
        }
        if (isset($data['sugar'])){
            $dataUpdate['sugar']= $data['sugar'];
        }
        if (!empty($dataUpdate)){
            $consultation->update(['first_diag'=>$dataUpdate]);
        }

        return redirect()->back();
    }

    public function postDiag(Request $request)
    {
        $data = \request()->all();
        $dataDiag = [];
        Validator::make($data,[
            'idconsult'=>'required',
            'diagnostic'=>'required'
        ],[]);

       $consultation = FegguConsultation::where('slug',$data['idconsult'])->first();
       $diag  = $consultation->diagnostic;
       //  dd($diag);
       if (!$consultation){
           return 'no data';
       }
       $diag []=$data['diagnostic'];

       //dd($diag);
       if (isset($data['diagnostic']) && !empty($data['diagnostic'])){
           $consultation->update(['diagnostic'=>$diag]);
       }

       return redirect()->back();
    }

    public function postPrescription()
    {
        //dd(\request()->all());
        $data = \request()->all();
        request()->validate([
            'idcons' => ['required'],
            'prescriptions.*.qte' => ['required', 'max:50','numeric'],
            'prescriptions.*.label' => ['required', 'max:100', 'string'],
            'prescriptions.*.dosage' => ['nullable', 'max:50', 'string'],
            'prescriptions.*.dosageText' => ['nullable', 'max:50', 'array'],
            'prescriptions.*.duration' => ['nullable', 'max:50', 'string'],
        ]);
        $prescriptions =  $data['prescriptions'];
        $consultation = FegguConsultation::where('slug',$data['idcons'])->first();
        if (!$consultation){
            return 'no data';
        }
        $arrPrescript = [];
        foreach ($prescriptions as $key => $prescript) {
            if ($prescript) {
                $arrPrescript[] = new FegguConsultationPrescription([
                    'label' => $prescript['label'],
                    'quantity' => $prescript['qte'],
                    'dosage' => $prescript['dosage'],
                    'dosage_text' => $prescript['dosageText'],
                    'duration' => $prescript['duration'],
                ]);
            }
        }
        $consultation->prescriptions()->saveMany($arrPrescript);

        return redirect()->back();
    }

    public function endConsultation()
    {
      $data=  \request('idconsultation');
      $cons = FegguConsultation::where('slug',$data)->first();
      if (!$cons){
          return 'no data';
      }

      $cons->update(['status'=>1]);
      return redirect()->back();
    }
    public function addHospitalisation()
    {
        $data = \request()->all();

        $consultation = FegguConsultation::where('slug',$data['idco'])->first();
        if(!$consultation) {
            return 'no data';
        }
        $dataInsert = [
            'consultation_id'=>$consultation->id,
            'patient_id'=>$consultation->patient_id,
            'hospital_id'=>$consultation->hospital_id,
            'doctor_id'=>Partner::user()->id,
            'slug'=>Str::uuid()->toString(),
            'type'=>$data['type'],
        ];
        FegguHospitalisation::create($dataInsert);
        return redirect()->back();
    }
    public function profile($id)
    {
       dd($id);
    }
    public function show($slug)
    {
        //dd($id);
        $consultation = FegguConsultation::with(['prescriptions','analyses'])->where('slug',$slug)->first();
        if (!$consultation){
            abort(404);
        }
        // dd($consultation);
        return view($this->templatePathPartner.'patient.show_consultation',[
            'title' => au_language_render('partner.patient.consultation'),
            'subTitle' => au_language_render('profile_patient'),
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'consultation' => $consultation,
        ]);
    }
    public function addAnalysis()
    {
        $data = request()->all();

        \request()->validate( [
            'analyses.*.note'=>'nullable|string|max:300',
            'analyses.*.analyse_id'=>'array',
            // 'analyse_id'=>'required|exists:'.AU_DB_PREFIX . 'category_analysis_detail,id',
            'analyses.*.emergency'=>'required|between:0,1',
            'idCons'=>'required',
        ],[]);
        $data = au_clean($data,[]);
        $consultation = FegguConsultation::where('slug',$data['idCons'])->first();
        $analyses=$data['analyses']??[];
        //  dd($analyses);
        $arrAnalyse = [];
        foreach ($analyses as $key => $analyse) {
            if ($analyse) {
                $arrAnalyse[] = new FegguConsultationAnalyse([
                    'analyse'=>$analyse['analyse_id'],
                    'doctor_id'=>Partner::user()->id,
                    'note'=>$analyse['note']??null,
                    'emergency'=>$analyse['emergency'],
                ]);
            }
        }
        //dd($arrAnalyse);
        $consultation->analyses()->saveMany($arrAnalyse);

        return redirect()->back();
    }

    public function updateDiag($id)
    {
        $consultation = FegguConsultation::findOrFail($id);
        $data = \request()->only('rapport');

        $validator = \Illuminate\Support\Facades\Validator::make($data, [
            'rapport'=>'required|string|max:500'
        ], [
            'rapport.required'   =>  __('the rapport is required'),
        ]);
        if($validator->fails()) {
            return response()->json(['status'=>'error', 'message'=>$validator->errors()]);
        }
        $message ='';
        $reload=false;

        $key = \request('type');
        if ($key){
            $consultation->update(['diagnostic'=>$data['rapport'],'updated_by'=>Partner::user()->id]);
            $message = __('Diagnostic updated success');
            $reload=false;
        }else{
            $consultation->update(['status'=>1,'doctor_id'=>Partner::user()->id,'view_at'=>showTime(),'diagnostic'=>$data['rapport']]);
            $message = __('New Diagnostic Added');
            $reload=false;
        }

        return response()->json(['status'=>'success', 'message'=>$message, 'reload'=>$reload]);
    }
    public function updateHealth()
    {
        $data = \request()->all();

        $validator = \Illuminate\Support\Facades\Validator::make($data, [
            'health'=>'required|string|max:300',
            'cons'=>'required|string|max:300',
        ], [
            'health.required'   =>  __('the result is required'),
            'cons.required'   =>  __('Oops error'),
        ]);
        if($validator->fails()) {
            return response()->json(['status'=>'error', 'message'=>$validator->errors()]);
        }
        $consult = FegguConsultation::where('slug',$data['cons'])->firstOrFail();
        if(is_null($consult->diagnostic) ) {
            return response()->json(['status'=>'error', 'message'=> __('Do Diagnostic Before Added Result')]);
        }

        $message ='';
        $reload=false;


        if ($data['action'] && $data['action']==='edit'){
            $message = __('Result updated success');
        }else{
            $message = __('Result Added success');
        }
        try {
            $consult->update(['result'=>$data['health']]);
        } catch (\Exception $exception){
            return $exception;
        }

        return response()->json(['status'=>'success', 'message'=>$message, 'reload'=>$reload]);
    }
    public function deleteDiag()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'message' => __('method not allow')]);
        }
        $idD = request('idD');
        $id = request('id');
        $consultation = FegguConsultation::findOrFail($idD);
        $diag = $consultation->detail->rapport;
        unset($diag[$id]);
        $dataArray = $diag;
        $consultation->detail->update(['rapport'=>$dataArray]);
        $message=__('Diagnostic Removed success');
        $reload=false;
        return response()->json(['status'=>'success', 'message'=>$message, 'reload'=>$reload]);
    }

    public function addPathology()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'message' => __('method not allow')]);
        }
        $data = request()->all();
        $validation_rule=[
            'state'=>'nullable|string|max:100',
            'observation'=>'nullable|string|max:300',
            'idp'=>'required|exists:'.AU_DB_PREFIX . 'feggu_user,id',
           // 'status'=>'required|between:0,1',
            'result'=>'required',
            'pathology'=>'required',
        ];
        $validator = Validator::make($data, $validation_rule, [

        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'error', 'message'=>$validator->errors()]);
        }
        $data = au_clean($data,[],true);
        $dataInsert = [
            'patient_id'=>$data['idp'],
            'level'=>$data['state'],
            'pathology'=>$data['pathology'],
            'hospital_id'=>session('partnerId'),
            'doctor_id'=>Partner::user()->id,
            'observation'=>$data['observation']??null,
            'result'=>$data['result']??null,
            'status'=>!empty($data['status'])?1:0,
        ];

          PatientPathology::create($dataInsert);
          $message= __('Pathology Added success');
          $reload=false;
        return response()->json(['status'=>'success', 'message'=>$message, 'reload'=>$reload]);
    }
    public function addAllergy()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'message' => __('method not allow')]);
        }
        $data = request()->all();
        $validation_rule=[
            'state'=>'nullable|string|max:100',
            'observation'=>'nullable|string|max:300',
            'idp'=>'required|exists:'.AU_DB_PREFIX . 'feggu_user,id',
           // 'status'=>'required|between:0,1',
            'result'=>'required',
            'allergy'=>'required',
        ];
        $validator = Validator::make($data, $validation_rule, [

        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'error', 'message'=>$validator->errors()]);
        }
        $data = au_clean($data,[],true);
        $dataInsert = [
            'patient_id'=>$data['idp'],
            'level'=>$data['state']??null,
            'allergy'=>$data['allergy'],
            'hospital_id'=>session('partnerId'),
            'doctor_id'=>Partner::user()->id,
            'observation'=>$data['observation']??null,
            'result'=>$data['result']??null,
            'status'=>!empty($data['status'])?1:0,
        ];

          PatientAllergy::create($dataInsert);
          $message= __('Analyse Added success');
          $reload=false;
        return response()->json(['status'=>'success', 'message'=>$message, 'reload'=>$reload]);
    }

    public function addPrescription()
    {
        $reload = false;
        $message='';
        $data = request()->all();
        $validation_rule=[
            'consult_pres'=>'required',
            'prescription'         => 'sometimes|required|array',
            'prescription.label.*'  => 'required_with:prescription',
            'prescription.quantity.*' => 'required_with:prescription|min:1',
            'prescription.dosage.*' => 'nullable|string|max:50',
            'prescription.dosage_text.*.value.*' => 'sometimes|required|array',
            'prescription.duration.*' => 'nullable|numeric',
            ];
        $validator = Validator::make($data, $validation_rule, [
            'prescription.*.label.required'   =>  'All prescription label is required',
            'prescription.*.quantity'           =>  'All prescription quantity is required',
        ]);

        if($validator->fails()) {
           return response()->json(['status'=>'error', 'message'=>$validator->errors()]);
        }
        $consultation = FegguConsultation::findOrFail($data['consult_pres']);
        if (!$consultation){
             return response()->json(['status'=>'error', 'message'=> __('Consultation not found')]);
        }

        $prescription = $data['prescription']??[];
       // dd($prescription);
        $dataInsert = [];

            foreach ($prescription as $group => $rowGroup) {
                if (count($rowGroup)) {
                    foreach ($rowGroup['label'] as $key => $nameAtt) {
                        if ($nameAtt) {
                            $dataAtt = au_clean(['label' => $nameAtt,
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
        $message = __('Prescriptions added success');
        $reload = false;
        return response()->json(['status'=>'success', 'message'=>$message, 'reload'=>$reload]);
    }



}
