<?php

namespace App\Http\Livewire\Partner\Ticket\Partials;

use Feggu\Core\Partner\Models\DepartmentPartner;
use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguUser;
use Feggu\Core\Partner\Partner;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Checkout extends Component
{

    public $insurer;
    public $insurer_number;
    public $amount;
    public $department=null;
    public $insurer_percentage;
    public $mode_payment='';
    public $exp_date;
    public $patient;
    public $net;
    public $assurance;
    public $address;
    public $is_apply = 0;
    public $dataPrint = [];

    protected $listeners =['add-ticket'=>'add'];

    public function resetFields()
    {
       $this->reset('insurer','insurer_number','amount','department',
    'insurer_percentage','mode_payment','exp_date','patient','net','assurance','is_apply');
    }
    public function mount()
    {
        //$this->emit('select2');
        $this->assurance   = getPartner()->insurers->pluck('insurer_name')->toArray();

    }

    public function apply()
    {
        $this->is_apply = 1;
        $this->insurer_percentage = $this->patient->assurance_percentage;
        $this->exp_date = $this->patient->date_expiration;
        $this->insurer_number = $this->patient->assurance_number;
        $this->insurer=$this->patient->assurance_service;
        $this->mode_payment = 'insurer';
        $this->dispatchBrowserEvent('apply');
        $this->net = $this->amount - (($this->amount * $this->insurer_percentage)/100);
    }

    public function removeApply()
    {

        $this->is_apply =0;
        $this->reset(['mode_payment','insurer_percentage','amount','net']);
        $this->dispatchBrowserEvent('remove-apply');
    }

    public function updatedDepartment()
    {
        $montant = DepartmentPartner::where('department_id',$this->department)->where('partner_id',getPartner()->id)->first();

        if ($montant){
            $this->amount = $montant->amount;
        }else{
            $this->amount = '';
        }
        if ($this->insurer_percentage >0){
            $this->dispatchBrowserEvent('apply');
            $this->mode_payment = 'insurer';
            $this->net = $this->amount - (($this->amount * $this->insurer_percentage)/100);
        }else{
            $this->dispatchBrowserEvent('remove-apply');
        }
    }


    protected  $rules = [
        'insurer'=> 'required',
        'insurer_percentage'=> 'required|numeric|min:1|max:100',
        'exp_date' =>'required|date',
        'insurer_number'=>'required|alpha_dash',
        ];

    protected function messages (){
      return  [
        'exp_date.after'=> __('Insurance Expired') ,
    ];
}

    public function updatedExpDate()
    {
        $this->validate([
            'exp_date' =>'required|date|after:'.date('Y-m-d', strtotime("+5 day")),
        ],[
            'exp_date.after' =>__('Insurance Expired'),
        ]);
    }

    public function updated($propertyName)
    {
        if ($this->mode_payment !== 'cash'){
            $this->validateOnly($propertyName);
        }

    }

    public function add($patient)
    {
       // dd($patient);
        $this->patient = FegguUser::find($patient);
        $this->address = $this->patient->address;
    }

    public function updatedModePayment()
    {
       if ($this->mode_payment !=='cash') {
           $this->net = $this->amount - (($this->amount * $this->insurer_percentage)/100);
       }else{
           $this->net = $this->amount;
       }
    }

    public function updatedInsurerPercentage()
    {
        $this->net = $this->amount - (($this->amount * $this->insurer_percentage)/100);
    }

    public function updatedInsurer()
    {
        $this->assurance   = getPartner()->insurers->pluck('insurer_name')->toArray();
        $this->validate([
            'insurer'=> 'required|in:'.implode(',',$this->assurance),//.json_encode($this->assurance),
        ],[
            'insurer.in'=> __('Insurance not accepted')
        ]);
    }

    public function submitForm()
    {
        $validation = [
            'department'=>'required',
            'address'=> 'required|string|max:200',
            'mode_payment'=>'required|string',
        ];

        if ($this->mode_payment !== 'cash' & !$this->is_apply){
            $validation['exp_date']='required|date|after:'.date('Y-m-d', strtotime("+5 day"));
            $validation['insurer']= 'required|in:'.implode(',',$this->assurance);
            $this->validate($validation,[],[
                'insurer.in'=> __('Insurance not accepted'),
                'exp_date.after' =>__('Insurance Expired'),
            ]);
        }


//dd('vl');
       $dataInsert = [
           'department_id' => $this->department,
           'address'=>$this->address,
           'type_payment'=>$this->mode_payment,
           'phone_number'=>$this->patient->mobil ??'',
          // 'doc_number'=>$this->patient->doc_number ??'',
           'user_id'=>Partner::user()->id,
           'hospital_id'=>getPartner()->id,
           'patient_id'=>$this->patient->id,
           'slug'=>Str::uuid()->toString(),
       ];
        if ($this->mode_payment !=='cash'){
            $this->net = $this->amount - (($this->amount * $this->insurer_percentage)/100);
            $dataInsert['assurance_service'] = $this->insurer;
            $dataInsert['assurance_number'] = $this->insurer_number;
            $dataInsert['assurance_percentage'] = $this->insurer_percentage;
            $dataInsert['assurance_exp_date'] = $this->exp_date??null;
        }else{
            $this->net = $this->amount;
        }
        $dataInsert['amount_ticket']= $this->amount;
        $dataInsert['net_ticket']=$this->net;
        $dataInsert['ticket']=generateTicket($this->department);

        $dataInsert = au_clean($dataInsert,[],true);
        FegguConsultation::create($dataInsert);
        $this->dataPrint = $dataInsert;
        $this->dataPrint['date']= showDate(now());
        $this->dataPrint['partner']=getPartner()->getTitle();
        $this->dataPrint['partner_phone']=getPartner()->office_phone;
        $this->dataPrint['e_mail']=getPartner()->email;
        $this->dataPrint['address']=getPartner()->address;
        $this->dataPrint['depart']=showDepart($this->department);
        $this->dispatchBrowserEvent('print-ticket',$this->dataPrint);
        $this->emitTo('partner.ticket.checkout-patient','added');
        $this->emitTo('partner.notification','success', __('Ticket Added success'));
        $this->resetFields();
        $this->dispatchBrowserEvent('reset-fields');

    }


    public function render()
    {
        return view('livewire.partner.ticket.partials.checkout',[
            'departments'=>getPartner()->departments
        ]);
    }
}
