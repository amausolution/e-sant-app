<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;
use App\Partner\Models\MedicineBrand;
use App\Partner\Models\MedicineCategory;
use App\Partner\Models\Pharmacy;
use App\Partner\Models\PharmacyProduct;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PharmacyProductController extends Controller
{
    public $pharmacy;

    public function __construct()
    {
        $this->pharmacy = Pharmacy::where('partner_id',session('partnerId'))->first();
    }

    public function index()
    {
        $id=$this->pharmacy->id??null;
        return Inertia::render('Partner/Pharmacy/Product/Index',[
            'filters' => \request()->all('search', 'trashed'),
            'title'=> __('New Medicine'),
            'products'=> PharmacyProduct::where('pharmacy_id',$id)  ->filter(Request::only('search', 'trashed'))
                ->paginate(20)
                ->withQueryString()
                ->through(fn ($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'status' => $product->status,
                    'sale' => $product->sale,
                    'bar_code' => $product->bar_code,
                    'brande' => $product->brand->name,
                    'category' => $product->categories()->get()->map->only('name'),

                ]),
        ]);
    }

    public function create()
    {
        $a = (new \Milon\Barcode\DNS2D)->getBarcodeHTML('4445645656', 'QRCODE');
    //  $code=  QrCode::format('svg')->mergeString(asset(getPartner()->logo), .3)->generate('amadou wade');
       // dd(au_html_render(DNS1D::getBarcodeSVG('4445645656', 'PHARMA2T')));
      //dd($code);
        return Inertia::render('Partner/Pharmacy/Product/Create',
        [
            'a'=>$a,
            'title'=>__('Add Medicine'),
            'categories'=> MedicineCategory::where('status',1)->get()->map->only('id','name'),
            'brands'=> MedicineBrand::where('status',1)->get()->map->only('id','name'),
          //  'code'=>au_html_render($code),
        ]);
    }

    public function store()
    {
        $data = Request::all();
        //dd($data);
       $request= Request::validate([
            'name'=>'required|string|max:200',
            'quantity'=>'numeric|min:1|required',
            'price'=>'required|min:1|numeric',
            'cost'=>'nullable|min:1|numeric',
            'category'=>'required',
           'description'=>'nullable|string|max:300',
           'side_effects'=>'nullable|string|max:200',
        ],[]);
       if ($data['can_detail']){
           Request::validate([
               'detail_price'=>'required'
           ]);
       }

    }
}
