<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;

use App\Partner\Models\Pharmacy;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class PharmacyController extends Controller
{
    public function index()
    {
        $pharmacy = Pharmacy::where('partner_id',session('partnerId'))->first();
        return Inertia::render('Partner/Pharmacy/Index',[
            'title'=>__('Pharmacy Partner'),
            'pharmacy' => $pharmacy,
            'products'=> $pharmacy->products()->get()->map(function ($product){
                return [
                    'stock'=>$product->sum('stock'),
                    'alert'=>$product->where('stock','<=',2)
                ];
            })
        ]);
    }

    public function updateInfo()
    {
      $data = Request::only('value','key');
      //dd($data);
      $pharm = getPartner()->pharmacy;
      Request::validate([
          'value'=>'required',
      ]);
      if (!$pharm){
          $dataInsert = [
              'partner_id'=>getPartner()->id,
              $data['key']=>$data['value'],
          ];
          Pharmacy::create($dataInsert);
      }else{
         $pharmacy= Pharmacy::where('partner_id',session('partnerId'))->first();
         $pharmacy->update([$data['key']=>$data['value']]);
      }
      return redirect()->back();
    }
}
