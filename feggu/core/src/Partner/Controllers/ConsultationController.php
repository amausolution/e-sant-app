<?php
namespace Feggu\Core\Partner\Controllers;

use App\Http\Controllers\RootPartnerController;
use Feggu\Core\Partner\Models\FegguConsultationDetail;
use Feggu\Core\Partner\Models\FegguConsultationPrescription;
use Feggu\Core\Partner\Models\FegguHospitalisation;
use Feggu\Core\Partner\Models\FegguConsultation;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Validator;
use DB;
class ConsultationController extends RootPartnerController
{

    public $groupBlood;
    public $departments;
    public function __construct()
    {
        parent::__construct();
        $this->groupBlood = DB::table(AU_DB_PREFIX.'feggu_blood')->pluck('blood');
    }
    public function index()
    {
        $partnerId = session('partnerId');
        if (!$partnerId){
           abort(404);
        }
        return Inertia::render('Partner/Consultation/Index');
    }

    public function postConsultation()
    {
       // dd('ok');
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin,
            [
                'content'=>'required|string',
                'health'=>'required|max:300',
                'pressure'=>'nullable|string',
                'temperature'=>'nullable|string',
                'heartbeat'=>'nullable|string',
                'label.*'=>'required|max:255',
                'duration.*'=>'required|max:55',
                'quantity.*'=>'required|max:100',
                'dosage.*'=>'required|max:100',
                'quantity_text.*'=>'required|max:100',
            ],
            []);
        if ($validator->fails()) {
            return redirect()->back()
                ->with('error',__('Error Validation'))
                ->withErrors($validator)
                ->withInput($data)
                ;
        }
        $consult = FegguConsultation::findOrFail($data['idConsultation']);

        if (!$consult){
            return 'no data';
        }
        $dataCons = [
            'doctor_id'=>\Partner::user()->id,
            'status'=>1,
        ];
       // dd($data);
        //$data = au_clean($data);
        $dataDetail = [
            'rapport'=>$data['content'],
            'temperature'=>$data['temperature'],
            'pressure'=>$data['pressure'],
            'heartbeat'=>$data['heartbeat'],
            'consultation_id'=>$consult->id,
            'health'=>$data['health'],
        ];

        $consult->update($dataCons);
        FegguConsultationDetail::create($dataDetail);
        $data_prescriptions = [];
        foreach ($data['label'] as $key => $value){
            $noon = !empty($data['noon'][$key])?'noon':null;
            $morning = !empty($data['morning'][$key])?'morning':null;
            $evening = !empty($data['evening'][$key])?'evening':null;
            $data_prescriptions[] =  new FegguConsultationPrescription([
                'label'=>$data['label'][$key],
                'quantity'=>$data['quantity'][$key],
                'dosage'=>$data['dosage'][$key],
                'duration'=>$data['duration'][$key],
                'dosage_text'=>json_encode([$noon,$evening,$morning]),
                'quantity_text'=>$data['quantity_text'][$key],
            ]);
        }
        // dd($dataDetail,$dataCons,$data_prescriptions);

        $consult->prescriptions()->saveMany($data_prescriptions);
        if (isset($data['hospitalisation'])){
          $dataHospit = [
              'patient_id'=>$consult->patient_id,
              'doctor_id'=>\Partner::user()->id,
              'consultation_id'=>$consult->id,
              'hospital_id'=>$consult->hospital_id,
              'type'=>$data['hospitalisation'],
              'slug'=>Str::uuid()->toString(),
          ];
        $hosp =   FegguHospitalisation::create($dataHospit);
        $hosp->consultations->attach($consult->id);
        }
        return redirect()->route('partner_patient.index')->with('success', __('consultation Save Success'));
    }
    public function edit($slug)
    {
        //dd($id);
        $consultation = FegguConsultation::with(['prescriptions','analyses','doctor','hospital','patient'])->where('slug',$slug)->first();
       // dd($consultation);
        return view($this->templatePathPartner.'screen.patient.consultation_patient',[
            'title' => au_language_render('partner.patient.profile'),
            'subTitle' => au_language_render('profile_patient'),
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'consultation' => $consultation,
        ]);
    }
    public function show($slug)
    {
        //dd($id);
        $consultation = FegguConsultation::with(['prescriptions','analyses'])->where('slug',$slug)->first();
       // dd($consultation);
        return view($this->templatePathPartner.'screen.patient.show_consultation',[
            'title' => au_language_render('partner.patient.consultation'),
            'subTitle' => au_language_render('profile_patient'),
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'consultation' => $consultation,
        ]);
    }
    public function printTicket($data)
    {
        $title = 'Print Ticket';
        return view($this->templatePathPartner.'invoice.print_ticket', compact('page_title', 'order','title'));
    }

    public function create()
    {

        return view($this->templatePathPartner.'screen.patient.consultation.new_consultation',[
            'title' => au_language_render('partner.patient.profile'),
            'subTitle' => au_language_render('profile_patient'),
            'title_description' => '',
            'icon' => 'fa fa-plus',
        ]);
    }
}
