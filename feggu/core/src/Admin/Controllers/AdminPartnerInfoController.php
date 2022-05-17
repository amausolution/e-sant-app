<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Admin\Models\AdminPartner;
use Feggu\Core\Admin\Models\AdminTemplate;
use Feggu\Core\Partner\Models\FegguLanguage;
use Feggu\Core\Partner\Models\FegguCurrency;

class AdminPartnerInfoController extends RootAdminController
{
    public $templates;
    public $currencies;
    public $languages;
    public $timezones;

    public function __construct()
    {
        parent::__construct();
        foreach (timezone_identifiers_list() as $key => $value) {
            $timezones[$value] = $value;
        }
        $this->templates = (new AdminTemplate)->getListTemplateActive();
        $this->currencies = FegguCurrency::getCodeActive();
        $this->languages = FegguLanguage::getListActive();
        $this->timezones = $timezones;
    }

    /*
    Update value config
    */
    public function updateInfo()
    {
        $data      = request()->all();
        $partnerId   = $data['partnerId'];
        $fieldName = $data['name'];
        $value     = $data['value'];
        $parseName = explode('__', $fieldName);
        $name      = $parseName[0];
        $lang      = $parseName[1] ?? '';
        $msg       = 'Update success';
        // Check store
        $store     = AdminPartner::find($partnerId);
        if (!$store) {
            return response()->json(['error' => 1, 'msg' => 'Partner not found!']);
        }

        if (!$lang) {
            try {
                if ($name == 'type') {
                    // Can not change type in here
                    $error = 1;
                    $msg = au_language_render('partner.admin.value_cannot_change');
                } elseif ($name == 'domain') {
                    if ($partnerId == AU_ID_ROOT || (au_config_global('MultiPartnerPro') && au_partner_is_partner($partnerId)) || au_config_global('MultiStorePro')) {
                        // Only store root can edit domain
                        $domain = au_process_domain_partner($value);
                        if (AdminPartner::where('domain', $domain)->where('id', '<>', $partnerId)->first()) {
                            $error = 1;
                            $msg = au_language_render('store.admin.domain_exist');
                        } else {
                            AdminPartner::where('id', $partnerId)->update([$name => $domain]);
                            $error = 0;
                        }
                    } else {
                        $error = 1;
                        $msg = au_language_render('store.admin.value_cannot_change');
                    }
                } elseif ($name == 'code') {
                    if (AdminPartner::where('code', $value)->where('id', '<>', $partnerId)->first()) {
                        $error = 1;
                        $msg = au_language_render('store.admin.code_exist');
                    } else {
                        AdminPartner::where('id', $partnerId)->update([$name => $value]);
                        $error = 0;
                    }
                } elseif ($name == 'template') {
                    AdminPartner::where('id', $partnerId)->update([$name => $value]);
                    //Install template for store
                    if (file_exists($fileProcess = resource_path() . '/views/templates/'.$value.'/Provider.php')) {
                        include_once $fileProcess;
                        if (function_exists('sc_template_install_store')) {
                            //Insert only specify store
                            $checkTemplateEnableStore = (new \Feggu\Core\Partner\Models\FegguPartnerCss)
                                ->where('template', $value)
                                ->where('partner_id', $partnerId)
                                ->first();
                            if (!$checkTemplateEnableStore) {
                                sc_template_install_store($partnerId);
                            }
                        }
                    }
                    $error = 0;
                } else {
                    AdminPartner::where('id', $partnerId)->update([$name => $value]);
                    $error = 0;
                }
            } catch (\Throwable $e) {
                $error = 1;
                $msg = $e->getMessage();
            }
        } else {
            // Process description
            $dataUpdate = [
                'partnerId' => $partnerId,
                'lang' => $lang,
                'name' => $name,
                'value' => $value,
            ];
            try {
                AdminPartner::updateDescription($dataUpdate);
                $error = 0;
            } catch (\Throwable $e) {
                $error = 1;
                $msg = $e->getMessage();
            }
        }
        return response()->json(['error' => $error, 'msg' => $msg]);
    }

    public function index()
    {
        $id = session('adminPartnerId');
        $partner = AdminPartner::find($id);
        if (!$partner) {
            $data = [
                'title' => au_language_render('store.admin.title'),
                'subTitle' => '',
                'icon' => 'fas fa-cogs',
                'dataNotFound' => 1
            ];
            return view($this->templatePathAdmin.'screen.store_info')
            ->with($data);
        }
        $data = [
            'title' => au_language_render('store.admin.title'),
            'subTitle' => '',
            'icon' => 'fas fa-cogs',
        ];
        $data['partner'] = $partner;
        $data['templates'] = $this->templates;
        $data['timezones'] = $this->timezones;
        $data['languages'] = $this->languages;
        $data['currencies'] =$this->currencies;
        $data['partnerId'] = $id;

        return view($this->templatePathAdmin.'screen.store_info')
        ->with($data);
    }
}
