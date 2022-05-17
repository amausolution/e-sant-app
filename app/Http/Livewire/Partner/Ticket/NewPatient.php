<?php

namespace App\Http\Livewire\Partner\Ticket;

use DateTime;
use Feggu\Core\Partner\Models\DepartmentPartner;
use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguUser;
use Livewire\Component;

class NewPatient extends Component
{
    public $insurer;
    public $insurer_number;
    public $amount;
    public $department=null;
    public $insurer_percentage;
    public $mode_payment='';
    public $exp_date;
    public $date_exp;
    public $patient;
    public $net;
    public $assurance;
    public $address;
    public $dataPrint = [];
    public $birthday;
    public $email;
    public $first_name;
    public $last_name;
    public $mobil;
    public $gender='';
    public $is_insured;
    public $phone2;
    public $piece;
    public $type_piece;
    public $godfather;
    public $check_insurer = true;


    public function submitForm()
    {
      $validation =   [
            'first_name'=>'required|string|min:2|max:30',
            'last_name'=>'required|string|min:2|max:30',
            'address'=>'required|string|max:250',
            'mobil'=>'nullable|string|regex:/[0-9]{9}/',
            'phone2'=>'nullable|string|regex:/[0-9]{9}/',
            'gender'=>'required|between:0,1',
        ];

      if ($this->is_insured){
          $validation['insurer_percentage']='required|min:1|max:100|numeric';
          $validation['insurer_number']='required|string|min:2';
          $validation['exp_date']='required|date|after:now';
          $validation['insurer']= 'required|string';
      }
     // dd('oj');
      if(!is_null($this->birthday) & (int)date('Y', time() - strtotime($this->birthday)) - 1970 <17){
          $validation['godfather']='required|string|max:90|min:4';
      }
      $this->validate($validation,[]);

      $dataInsert = [
          'first_name'=> $this->first_name,
          'last_name'=> $this->last_name,
          'gender'=>$this->gender,
          'mobil'=>$this->mobil,
          'email'=>strtolower($this->email)??'',
          'address'=>$this->address,
          'assurance'=>$this->is_insured,
          'birthday'=>$this->birthday,
          'phone_urgency'=>$this->phone2,
      ];

        if ($this->is_insured){
            $dataInsert['assurance_service']=$this->insurer;
            $dataInsert['assurance_number']=$this->insurer_number;
            $dataInsert['assurance_percentage']=$this->insurer_percentage;
            $dataInsert['date_expiration']=$this->exp_date;
        }

        $age = (int)date('Y', time() - strtotime($this->birthday)) - 1970;
        if ($age < 17){
          $dataInsert['godfather']= $this->godfather;
        }else{
            $dataInsert['type_piece']=$this->type_piece;
            $dataInsert['number_piece']=$this->piece;
            $dataInsert['piece_exp']=$this->date_exp;
        }

        $dataInsert = au_clean($dataInsert,[],true);
        $ticket = FegguUser::create($dataInsert);

        $doc = generateDocNumber($this->first_name,$this->last_name,$ticket->id);
        $ticket->update(['doc_number'=>$doc]);

        $dataConsultation = [
            'department_id'=>$this->department,
            'ticket'=>generateTicket($this->department),
            'amount_ticket'=>$this->amount,
            'net_ticket'=>$this->net,
            'type_payment'=>$this->mode_payment,
            'age'=>showAge($this->birthday),
            'discount'=>$this->amount - $this->net,
            'hospital_id'=>getPartner()->id,
            'user_id'=>\Partner::user()->id,
            'patient_id'=>$ticket->id,
            'address'=>$this->address,
        ];

        $dataConsultation = au_clean($dataConsultation,[],true);
        FegguConsultation::create($dataConsultation);

        $this->dataPrint = $dataConsultation;
        $this->dataPrint['date']= showDate(now());
        $this->dataPrint['partner']=getPartner()->getTitle();
        $this->dataPrint['partner_phone']=getPartner()->office_phone;
        $this->dataPrint['e_mail']=getPartner()->email;
        $this->dataPrint['address']=getPartner()->address;
        $this->dataPrint['depart']=showDepart($this->department);

        //dd($this->dataPrint);
        $this->dispatchBrowserEvent('print-ticket',$this->dataPrint);
        $this->emitTo('partner.ticket.checkout-patient','added');
        $this->emitTo('partner.notification','success', __('Ticket Added success'));
        $this->resetFields();
    }

    public function updatedEmail()
    {
      $this->validate(['email'=>'nullable|email|max:90|unique:'.FegguUser::class.',email']) ;
    }

    public function updatedMobil()
    {
        $this->validate(['mobil'=>'nullable|max:9|regex:/[0-9]{9}/'],[
            'mobil.regex'=> __('Format Mobil Invalid'),
            'mobil.max'=> __('Format Mobil Invalid'),
        ]);
    }
    public function updatedExpDate()
    {
        $this->validate([
            'exp_date' =>'required|date|after:now',
        ],[
            'exp_date.after' =>__('Insurance Expired'),
        ]);
    }
    public function updatedPhone2()
    {
        $this->validate(['phone2'=>'nullable|max:9|regex:/[0-9]{9}/'],[
            'phone2.regex'=> __('Format Mobil Invalid'),
            'phone2.max'=> __('Format Mobil Invalid'),
        ]);
    }

    public function updatedBirthday()
    {
        $age = (int)date('Y', time() - strtotime($this->birthday)) - 1970;
        if ($age < 17){
            $this->validate([
              'godfather'=>'required|string|max:90|min:4',
            ],[
                'godfather.required'=> __('The Tutor is required')
            ]);
        }
    }

    public function updatedGodfather()
    {
      $this->validate(['birthday'=>'required|date'])  ;
    }

    public function updatedDepartment()
    {
        $montant = DepartmentPartner::where('department_id',$this->department)->where('partner_id',getPartner()->id)->first();
        if ($montant){
            $this->amount = $montant->amount;
            if ($this->is_insured){
             $this->mode_payment = 'insurer';
             $this->net = $this->amount - (($this->amount * $this->insurer_percentage) / 100);
            }else{
                $this->dispatchBrowserEvent('department-updated');
                $this->net = $this->amount;
            }
        }else{
            $this->amount = '';
        }

    }


    public function updatedModePayment()
    {
        if ($this->mode_payment !=='cash') {
            $this->net = $this->amount - (($this->amount * $this->insurer_percentage)/100);
        }else{
            $this->net = $this->amount;
        }
    }
    public function updatedInsurer()
    {
        $ass  = getPartner()->insurers->pluck('insurer_name')->toArray();
        if (in_array($this->insurer, $ass, true)){
            $this->check_insurer = true;
        }else{
            $this->check_insurer = false;
        }
        //dd($this->check_insurer);
    }

    public function updatedIsInsured()
    {
        if (!$this->is_insured){
            $this->dispatchBrowserEvent('show-department');
        }

    }

    public function render()
    {
        return view('livewire.partner.ticket.new-patient') ->extends('layouts.layout')
            ->section('main');
    }
    private function resetFields(){
       $this->reset(['first_name','last_name','address','birthday','mobil','email','piece',
           'type_piece','godfather','phone2','is_insured','insurer',
           'insurer_number','insurer_percentage','exp_date','date_exp','dataPrint','department','amount','net','mode_payment','gender'
       ]);
    }
}
