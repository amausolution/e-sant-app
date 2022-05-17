<?php

namespace App\Http\Livewire\Partner\Ticket;

use Feggu\Core\Partner\Models\FegguUser;
use Livewire\Component;
use Livewire\WithPagination;

class CheckoutPatient extends Component
{
    public $identifier = '';
    public $first_name = '';
    public $last_name = '';
    public $gender=null;
    public $dob=null;
    public  $perPage = 20;
    public $showForm= false;
    public $disabled='back';

    public function resetField()
    {
        $this->identifier = '';
        $this->first_name = '';
        $this->last_name ='';
        $this->gender = null;
        $this->dob = null;
        $this->showForm = false;
    }

    use WithPagination;
    protected $listeners = ['added','cancel'];

    public function added()
    {
        $this->resetField();
        $this->dispatchBrowserEvent('new-ticket');
    }

    public function addPatient($idP)
    {
        $this->emitTo('partner.ticket.partials.checkout','add-ticket',$idP);
        $this->dispatchBrowserEvent('close-search');
        $this->showForm = true;
    }



    public function render()
    {
        $query = FegguUser::where(function ($q){
            if (strlen($this->first_name)>1) {
                $first_name = $this->first_name;
                $q->where('first_name', 'like', "{$first_name}%");
            }
            if (strlen($this->last_name)>1) {
                $last_name = $this->last_name;
                $q->where('last_name', 'like', "{$last_name}%");
            }
            if (strlen($this->identifier)>1) {
                $matriculation = $this->identifier;
                $q->where('matricule', '=', $matriculation)->orWhere('doc_number','=',$matriculation)->orWhere('mobil','=',$matriculation);
            }
            if ($this->gender !== null) {
                $q->where('gender', $this->gender);
            }

            if ($this->dob !==null){
                $q->whereDate('birthday',date('Y-m-d'));
            }
        });
        $data = $query->paginate($this->perPage);
        return view('livewire.partner.ticket.checkout-patient',[
            'dataSearch'=>$data,
        ])
            ->extends('layouts.layout')
            ->section('main');
    }
}
