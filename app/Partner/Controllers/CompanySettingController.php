<?php


namespace App\Partner\Controllers;


use App\Http\Controllers\RootPartnerController;
use App\Partner\Models\SettingPartner;
use Feggu\Core\Admin\Models\AdminConfig;
use Feggu\Core\Admin\Models\AdminPage;
use Feggu\Core\Admin\Models\AdminTemplate;
use Feggu\Core\Partner\Models\FegguCountry;
use Feggu\Core\Partner\Models\FegguCurrency;
use Feggu\Core\Partner\Models\FegguLanguage;
use Feggu\Core\Partner\Models\FegguPartner;

class CompanySettingController extends RootPartnerController
{
    public $templates;
    public $currencies;
    public $languages;
    public $timezones;
    public $countries;

    public function __construct()
    {
       Parent::__construct();


        foreach (timezone_identifiers_list() as $key => $value) {
            $timezones[$value] = $value;
        }
        $this->templates = (new AdminTemplate)->getListTemplateActive();
        $this->currencies = FegguCurrency::getCodeActive();
        $this->languages = FegguLanguage::getListActive();
        $this->countries = FegguCountry::getCodeAll();
        $this->timezones = $timezones;
    }

    public function company()
    {
        $id = session('partnerId')??'';
        $company = FegguPartner::find($id);
        if (!$company){
            abort(404);
        }
        return view($this->templatePathPartner.'settings.company',[
            'title'=> __('Settings Company'),
            'company'=>$company,
            'countries'=>$this->countries,
            'subTitle'=> __('Update Company')
        ]);
    }
    public function localization()
    {

        if (!getPartner()->setting){
            abort(404);
        }
        return view($this->templatePathPartner.'settings.localisation',[
            'title'=> __('Settings Localisation'),
            'company'=>!getPartner()->setting,
            'subTitle'=> __('Update Localisation'),
            'timezones'=>$this->timezones,
            'languages'=>$this->languages,
            'currencies'=>$this->currencies,
        ]);
    }

    /*
     Update value config store
     */
    public function updateCompany()
    {
        $data = request()->all();

        $partnerId = $data['partnerId'] ?? '';
        if (!$partnerId) {
            return response()->json(
                [
                    'reload' => false,
                    'status' => 'error',
                    'message'   => __('Store ID can not empty!'),
                ]
            );
        }
        $dataUpdate = [];

        try {
            SettingPartner::find($partnerId)
                ->update($dataUpdate);
            $reload = true;
            $type = 'success';
            $message = __('Info Updated Success');
        } catch (\Throwable $e) {
            $reload = false;
            $message = $e->getMessage();
            $type='error';
        }
        return response()->json(
            [
                'message' => $message,
                'status' => $type,
                'reload' => $reload,
            ]
        );
    }

    public function show($code)
    {
        $store_id = session('partnerStorId')??'';
        $store = FegguPartner::findOrFail($store_id);
        if (!$store){
            return __('No Data Found');
        }
        return view($this->templatePathPartner.'screen.setting.show',[
            'title'=>__('Settings Caompany'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',
            'store'=>$store,
        ]);
    }

    /**
     * Update config global
     *
     * @return  [type]  [return description]
     */
    public function updateGlobal()
    {
        $id = session('partnerId')??'';
        //dd('ok');
        // $store_id = session('partnerId')??'';
        $store = FegguPartner::findOrFail($id);
        if (!$store){
            abort(404);
        }

        $data = [
            'title' => __('Default Config'),
            'subTitle' => '',
            'icon' => 'fas fa-cogs',
        ];
        $data['store']=$store;
        return view($this->templatePathPartner.'screen.setting.config_global',$data);
    }
}
