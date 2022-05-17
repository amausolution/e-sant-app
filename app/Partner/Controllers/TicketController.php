<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RootPartnerController;
use Feggu\Core\Partner\Models\DepartmentPartner;
use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\FegguUser;
use Feggu\Core\Partner\Models\PartnerUser;
use Feggu\Core\Partner\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class TicketController extends RootPartnerController
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
      //  dd(\request('patient_id'));
        return view('partner.ticket', [
            'patients'=> FegguUser::where( function ($query){
                $query->when(\request('patient_id'),  function ($q, $doc_number){
                    $q->where('doc_number',$doc_number)->orWhere('matricule',$doc_number);
                });
                   $query->when(\request('first_name'),function ($q,$first_name){
                       $q->where('first_name','like',"%{$first_name}%");
                   });
            })->get()
        ]);

    }

    public function return_patient()
    {

        return Inertia::render('Partner/Ticket/RetuenPatient',[
            'patients'=>FegguUser::query()
                ->when((new \Illuminate\Http\Request)->input('patient_i'), function ($query, $patient_id){
                $query->where('doc_number',$patient_id)
                      ->orWhere('matricule',$patient_id);
            })->when((new \Illuminate\Http\Request)->input('first_name'),function ($query, $first_name){
                $query->where('first_name','like',"{$first_name}");
                })->when((new \Illuminate\Http\Request)->input('last_name'),function ($query, $last_name){
                $query->where('first_name','like',"{$last_name}");
                })->when((new \Illuminate\Http\Request)->input('gender'),function ($query, $gender){
                $query->where('gender','=',"{$gender}");
                })->paginate(10),
        ]);
    }
   public function new_patient()
    {
        return view($this->templatePathPartner.'partner.new_patient',[
            'title' => __('New Ticket Return'),
            'subTitle' => __('Patient Return'),
            'title_description' => '',
            'icon' => 'fa fa-plus',
            'patient' => [],
            'bloods'       => $this->groupBlood,
            'url_action'        => au_route_partner('store.ticket'),
        ]);
    }

    //store ticket
    public function storeTicket()
    {
       $data = request()->all();
   //    dd($data);
        $dataOrigin = request()->all();

        $array_validator =[
            'department'=>'required',
            'type_payment'=>'required',
        ];
        //si assurance et que c'est un return patient validation
        if (isset($data['type_payment']) && $data['type_payment'] === 'insurer' && $data['type'] !== 'new') {
            $array_validator['assurance_service'] = 'required|max:50';
            $array_validator['assurance_number'] = 'required|string|max:50';
            $array_validator['assurance_percentage'] = 'required|numeric|min:1|max:100';
            $array_validator['date_expiration'] = 'required|date';
        }

        if (isset($data['assurance'])){
            $array_validator['assurance_service'] = 'required|max:50';
            $array_validator['assurance_number'] = 'required|string|max:50';
            $array_validator['assurance_percentage'] = 'required|numeric|min:1|max:100';
            $array_validator['date_expiration'] = 'required|date';
        }
        if ($data['type'] && $data['type']==='new'){
            $array_validator['firstname'] = 'required|string|max:50';
            $array_validator['lastname'] = 'required|string|max:50';
            $array_validator['email']    = 'required|string|email|max:255';
            $array_validator['address']    = 'required|string|max:255';
            $array_validator['gender']='required|numeric|between:0,1';
            $array_validator['birthday']='required|date';
            $array_validator['phone']='required|max:20';
        }else{
            $array_validator['idP']='required';
        }

      $validator=   Validator::make($dataOrigin,$array_validator, [
            'first_name.required'=>au_language_render('partner.validation.first_name_required')
        ]);
        if($validator->fails()) {
            return response()->json(['status'=>'error', 'message'=>$validator->errors()]);
        }
        $depart = DepartmentPartner::where('department_id',$data['department'])->where('partner_id',session('partnerId'))->first();
        if (isset($data['type']) && $data['type'] === 'new'){
            $dataPatient = [
                'first_name'=>$data['firstname'],
                'last_name'=>$data['lastname'],
                'phone'=>$data['phone'],
                'email'=> strtolower($data['email']),
                'gender'=>$data['gender'],
                'address'=>$data['address'],
                'birthday'=>$data['birthday'],
            ];
            if (isset($data['assurance'])){
                $dataPatient['assurance']=!empty($data['assurance'])?1:0;
                $dataPatient['assurance_service'] = $data['assurance_service'];
                $dataPatient['assurance_number'] = $data['assurance_number'];
                $dataPatient['assurance_percentage'] = $data['assurance_percentage'];
                $dataPatient['date_expiration'] = $data['date_expiration'];
            }
        }

        $dataTicket = [
            'department_id'=>$data['department'],
            'type_payment'=>$data['type_payment'],
            'user_id'=>Partner::user()->id,
            'hospital_id'=>getPartner()->id,
        ];
        if ($data['type_payment'] !=='cash'){
            $amount = $depart->amount - (($depart->amount * $data['assurance_percentage'])/100);
            $dataTicket['assurance_service'] = $data['assurance_service'];
            $dataTicket['assurance_number'] = $data['assurance_number'];
            $dataTicket['assurance_percentage'] = $data['assurance_percentage'];
            //$dataTicket['date_expiration'] = $data['date_expiration'];
        }else{
            $amount = $depart->amount;
        }
        $dataTicket['amount_ticket']= $depart->amount;
        $dataTicket['net_ticket']=$amount;
        $dataTicket['ticket']=generateTicket($data['department']);
       //dd($dataPatient,$dataTicket);
        if (isset($data['type']) && $data['type'] === 'new'){
            $patient = FegguUser::create($dataPatient);
            $doc = generateDocNumber($patient->first_name,$patient->last_name,$patient->id);
            $patient->update(['doc_number'=>$doc]);
        }else{
            $patient = FegguUser::find($data['idP']);
        }

        $dataTicket['patient_id']=$patient->id;

        $message= __('Patient added success');
        FegguConsultation::create($dataTicket);
        $reload=false;
        return response()->json(['status'=>'success', 'message'=>$message, 'reload'=>$reload]);
    }

    /*
       public function create()
       {
           $part =  FegguPartner::where('id',session('partnerId'))->first();
           $data = [
               'title' => __('New Consultation'),
               'subTitle' => '',
               'title_description' => '',
               'icon' => 'fa fa-plus',
               'patient' => [],
               'bloods'       => $this->groupBlood,
               'url_action'        => au_route_partner('partner_patient.create'),
           ];
           return view($this->templatePathPartner.'accueil.create',[
               'data'=>$data,
               'departments'=>$part,
           ]);
       }*/



}
