<?php
namespace App\Partner\Controllers;

use App\Http\Controllers\RootPartnerController;
use App\Models\Plugin;
use App\Partner\Models\Laboratory;
use Feggu\Core\Admin\Models\AdminPartner;
use Feggu\Core\Partner\Models\FegguCountry;
use Feggu\Core\Partner\Models\FegguDepartment;
use Feggu\Core\Partner\Models\FegguLanguage;
use Feggu\Core\Partner\Models\FegguCurrency;
use Feggu\Core\Admin\Models\AdminConfig;
use Feggu\Core\Admin\Models\AdminTemplate;
use Feggu\Core\Admin\Models\AdminPage;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\FegguRegion;
use Feggu\Core\Partner\Models\PartnerConfig;
use http\Exception;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class PartnerConfigController extends RootPartnerController
{
    public $countries;
    public $regions;
    public $currencies;
    public $languages;
    public $timezones;
    public $departments;

    public function __construct()
    {
        parent::__construct();
        foreach (timezone_identifiers_list() as $key => $value) {
            $timezones[$value] = $value;
        }
        $this->currencies = FegguCurrency::getCodeActive();
        $this->languages = FegguLanguage::getListActive();
        $this->countries = FegguCountry::getCodeAll();
        $this->regions = FegguRegion::getCodeAll();
        $this->departments = FegguDepartment::getCodeAll();
        $this->timezones = $timezones;
    }

    public function updateConfig()
    {
        $request = Request::only('key','value');

        $partnerId = session('partnerId');
      // dd($request);
        //dd(au_partner_config($request['key'],$partnerId));
        if (!is_null(au_partner_config($request['key'],$partnerId))){
            PartnerConfig::where('partner_id', $partnerId)
                ->where('key', $request['key'])->first()
                ->update(['value' => $request['value']]);
        }else{
            PartnerConfig::create([
                'partner_id'=>$partnerId,
                'key'=>$request['key'],
                'value'=>$request['value']
            ]);
        }
        return redirect()->back();
    }
    public function config()
    {
       // dd(au_partner_config());
        return Inertia::render('Partner/Setting/Settings',[
            'title'=> __('Config Global'),
            'total'=>[
                'nbr_ambulance'=> getPartner()->ambulances->count(),
                'rooms'=>getPartner()->rooms->count(),
                'operatingRooms'=>getPartner()->roomsBloc->count(),
            ],
        ]);
    }

    public function index()
    {
        $id = session('partnerId');
        $data = [
            'title' => __('Default Config'),
            'subTitle' => '',
            'icon' => 'fas fa-cogs',
        ];

        $data['smtp_method'] = ['' => 'None Secirity', 'TLS' => 'TLS', 'SSL' => 'SSL'];
        $data['timezones']                      = $this->timezones;
        $data['languages']                      = $this->languages;
        $data['currencies']                     = $this->currencies;
        $data['regions']                        = $this->regions;
        $data['countries']                      = $this->countries;
        $data['departments']                     = $this->departments;
        $data['partnerId']                       = $id;
        $data['setting']                         = FegguPartner::where('id',session('partnerId'))->get()->map(function ($partner){
            return [
                'id'=> $partner->id,
                'title'=> $partner->title??'',
                'description'=>$partner->description??'',
                'keyword'=>$partner->keyword??'',
                'phone'=>$partner->phone,
                'domain'=>$partner->domain,
                'post_code'=>$partner->post_code,
                'fax' =>$partner->fax,
                'office_phone'=>$partner->office_phone,
                'email'=>$partner->email,
                'responsible'=>$partner->responsible,
                'address'=>$partner->address,
                'country'=>$partner->country,
                'region'=>$partner->region,
                'department'=>$partner->department,
                'district'=>$partner->district,
                'warehouse'=>$partner->warehouse,
                'type'=>$partner->type,
                'partner'=>$partner->partner
            ];
        })->first();
      // dd($data['setting']);
        return Inertia::render('Partner/Setting/Info')
        ->with($data);
    }
    public function email()
    {
        $id = session('partnerId');
        $data = [
            'title' => __('Default Config'),
            'subTitle' => '',
            'icon' => 'fas fa-cogs',
        ];

        $data['smtp_method'] = ['' => 'None Secirity', 'TLS' => 'TLS', 'SSL' => 'SSL'];
        $data['timezones']                      = $this->timezones;
        $data['languages']                      = $this->languages;
        $data['currencies']                     = $this->currencies;
        $data['regions']                        = $this->regions;
        $data['countries']                      = $this->countries;
        $data['departments']                     = $this->departments;
        $data['partnerId']                       = $id;
        $data['setting']                         = FegguPartner::where('id',session('partnerId'))->get()->map(function ($partner){
            return [
                'id'=> $partner->id,
                'type'=>$partner->type,
                'partner'=>$partner->partner
            ];
        })->first();
      // dd($data['setting']);
        return Inertia::render('Partner/Setting/Email')
        ->with($data);
    }

    public function install()
    {
      //dd('install') ;
        try {
            PartnerConfig::create([
                'code'=>'Laboratory',
                'group'=>'Plugins',
                'key'=>'LABO',
                'value'=>1,
                'partner_id'=> session('partnerId'),
            ]);
            Laboratory::create([
                'partner'=>session('partnerId'),
            ]);
         }catch (\Throwable $e){
            return $e;
        }
        return redirect()->back();
    }
    public function disable()
    {
        $data = Request::all();
  //    dd($data) ;
        $data = trim($data['key']);
        try {
          (new Plugin($data))->disable();
         }catch (\Throwable $e){
            return $e;
        }
        return redirect()->back();
    }
    public function enable()
    {
        $data = Request::all();
        //    dd($data) ;
        $data = trim($data['key']);
        try {
            (new Plugin($data))->enable();
        }catch (\Throwable $e){
            return $e;
        }
        return redirect()->back();
    }


    /*
    Update value config store
    */
    public function update()
    {
        $partnerId = session('partnerId') ?? '';
        $data = Request::all();
       // dd($data);
        $part = AdminPartner::find($partnerId);
        Request::validate([
            'title'=>'required|max:200',
            'keyword'=>'nullable|string|max:100',
            'description'=>'nullable|string|max:300',
            'phone'=>'nullable|regex:/^[0-9\-]{6,12}$/',
            'fax'=>'nullable|regex:/^[0-9\-]{6,12}$/',
            'office_phone'=>'required|regex:/^[0-9\-]{6,12}$/',
            'email'=>'required|email|max:255',
            'address'=>'required|string|max:300',
            'responsible'=>'required|string',
            'country'=>'required|string|max:5',
            'region'=>'required|string|max:10',
            'department'=>'required|string|max:10',
            'district'=>'required|string|max:100',
            'warehouse'=>'nullable|string|max:100',

        ],[]);
        if (!$partnerId || !$part) {
           return  'no data';
        }
       $dataUpdate = [
            'title'=> $data['title'],
            'keyword'=> $data['keyword'],
            'description'=> $data['description'],
            'phone'=> $data['phone'],
            'fax'=> $data['fax'],
            'office_phone'=> $data['office_phone'],
            'email'=> $data[ 'email'],
            'address'=> $data['address'],
            'responsible'=> $data['responsible'],
            'country'=> $data['country'],
            'region'=> $data['region'],
            'department'=> $data['department'],
            'district'=> $data[ 'district'],
            'warehouse'=> $data['warehouse'],
       ];
        try {
            $part->update($dataUpdate);
        } catch (\Throwable $e) {
           return $e;
        }
        return redirect()->back();
    }

    public function plugin()
    {

       return Inertia::render('Plugins',
       [
           'title'=> __('Plugins'),

       ]
       );
    }
}
