<?php

namespace App\Http\Livewire\Partner;

use Livewire\Component;

class Notification extends Component
{
    protected $listeners = ['success','error','warning','info'];

    public function success($message)
    {
       $this->dispatchBrowserEvent('success',['message'=>$message]);
    }
    public function error($message)
    {
        $this->dispatchBrowserEvent('error',['message'=>$message]);
    }
    public function warning($message)
    {
        $this->dispatchBrowserEvent('warning',['message'=>$message]);
    }

    public function info($message)
    {
        $this->dispatchBrowserEvent('info',['message'=>$message]);
    }
    public function render()
    {
        return view('livewire.partner.notification');
    }
}
