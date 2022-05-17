<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Admin\Models\AdminPartner;
use Feggu\Core\Admin\Models\AdminTemplate;
use Feggu\Core\Partner\Models\FegguLanguage;
use Feggu\Core\Partner\Models\FegguCurrency;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Models\PartnerUser;
use Validator;

class AdminPartnerController extends RootAdminController
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

    public function edit($id)
    {
        $partner = AdminPartner::find($id);
        if (!$partner) {
            $data = [
                'title' => au_language_render('feggu.admin.title'),
                'subTitle' => '',
                'icon' => 'fas fa-cogs',
                'dataNotFound' => 1
            ];
            return view($this->templatePathAdmin.'screen.feggu_info')
                ->with($data);
        }
        $data = [
            'title' => au_language_render('feggu.admin.title'),
            'subTitle' => '',
            'icon' => 'fas fa-cogs',
        ];
        $data['partner'] = $partner;
        $data['templates'] = $this->templates;
        $data['timezones'] = $this->timezones;
        $data['languages'] = $this->languages;
        $data['currencies'] =$this->currencies;
        $data['partnerId'] = $id;

        return view($this->templatePathAdmin.'screen.partner_info')
            ->with($data);
    }
    /*
    Update value config
    */
    public function updateInfo($id)
    {
        $data      = request()->all();
        $partnerId   = $id;
        $fieldName = $data['name'];
        $value     = $data['value'];
        $parseName = explode('__', $fieldName);
        $name      = $parseName[0];
        $lang      = $parseName[1] ?? '';
        $msg       = 'Update success';
        // Check feggu
        $partner     = AdminPartner::find($partnerId);
        if (!$partner) {
            return response()->json(['error' => 1, 'msg' => 'Store not found!']);
        }

        if (!$lang) {
            try {
                if ($name == 'type') {
                    // Can not change type in here
                    $error = 1;
                    $msg = au_language_render('feggu.admin.value_cannot_change');
                } elseif ($name == 'domain') {
                    if ($partnerId == AU_ID_ROOT || (au_config_global('MultiPartnerPro') && au_partner_is_partner($partnerId)) || au_config_global('MultiStorePro')) {
                        // Only feggu root can edit domain
                        $domain = au_process_domain_partner($value);
                        if (AdminPartner::where('domain', $domain)->where('id', '<>', $partnerId)->first()) {
                            $error = 1;
                            $msg = au_language_render('feggu.admin.domain_exist');
                        } else {
                            AdminPartner::where('id', $partnerId)->update([$name => $domain]);
                            $error = 0;
                        }
                    } else {
                        $error = 1;
                        $msg = au_language_render('feggu.admin.value_cannot_change');
                    }
                } elseif ($name == 'code') {
                    if (AdminPartner::where('code', $value)->where('id', '<>', $partnerId)->first()) {
                        $error = 1;
                        $msg = au_language_render('feggu.admin.code_exist');
                    } else {
                        AdminPartner::where('id', $partnerId)->update([$name => $value]);
                        $error = 0;
                    }
                } elseif ($name == 'template') {
                    if (AdminPartner::where('code', $value)->where('id', '<>', $partnerId)->first()) {
                        $error = 1;
                        $msg = au_language_render('feggu.admin.code_exist');
                    } else {
                        AdminPartner::where('id', $partnerId)->update([$name => $value]);

                        //Install template for feggu
                        if (file_exists($fileProcess = resource_path() . '/views/templates/'.$value.'/Provider.php')) {
                            include_once $fileProcess;
                            if (function_exists('au_template_install_feggu')) {
                                //Insert only specify store
                                $checkTemplateEnableStore = (new \Feggu\Core\Admin\Models\AdminStoreBlockContent)
                                    ->where('template', $value)
                                    ->where('partner_id', $partnerId)
                                    ->first();
                                if (!$checkTemplateEnableStore) {
                                    au_template_install_feggu($partnerId);
                                }
                            }
                        }
                        $error = 0;
                    }
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
        $data = [
            'title' => au_language_render('feggu.admin.title'),
            'subTitle' => '',
            'icon' => 'fas fa-cogs',
        ];


        return view($this->templatePathAdmin.'screen.admin_partner')
        ->with($data);
    }
    public function create()
    {
        $data = [
            'title' => au_language_render('feggu.admin.title_new_partner'),
            'subTitle' => '',
            'icon' => 'fas fa-cogs',
        ];

        return view($this->templatePathAdmin.'screen.admin_partner_create')
            ->with($data);
    }

    public function postCreate()
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin,
            [
                'type'     =>'required|max:200',
                'name.*'   =>'required|max:200',
                'keyword.*'=>'nullable|max:200',
                'description.*'=>'nullable|max:300',
                'phone'    =>'required',
                'office_phone'=>'required',
                'code'     => 'required|regex:/(^([0-9A-Za-z@\._]+)$)/|unique:"'.AdminPartner::class.'",code|string|max:20|min:3',
                'email'    => 'nullable|string|email|max:255|unique:"'.AdminPartner::class.'",email',
                'address'  =>'nullable|max:300',
                'map'      =>'nullable|string',
            ],[
                'name.*.required'=>au_language_render('validation.required'),
                'keyword.*.max'=>au_language_render('validation.max_200'),
                'description.*.max'=>au_language_render('validation.max_300'),
                'address.max'=>au_language_render('validation.max_300'),
                'phone.required'=>au_language_render('validation.required'),
                'office_phone.required'=>au_language_render('validation.required'),
                'code.required'=>au_language_render('validation.required'),
                'code.regex'=>au_language_render('validation.format_invalid'),
                'email.email'=>au_language_render('validation.required'),
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $dataInsert = [
            'code'=>$data['code'],
            'map'=>$data['map'],
            'email'=>$data['email']??'',
            'type'=>$data['type'],
            'address'=>$data['address'],
            'phone'=>$data['phone'],
            'admin_id'=>\Admin::user()->id,
            'office_phone'=>$data['office_phone'],
            'status'=> !empty($data['status']) ? 1 : 0,
        ];

       // dd($dataInsert);
        $new_part = AdminPartner::create($dataInsert);
        $dataPartner = [
            'username' => strtolower($data['username']),
            //'name'    => 'administrateur',
            'matricule'=>'FEG-A'.$new_part->id.rand('111111','999999'),
            'email'    => strtolower($data['email']),
            'password' => bcrypt($data['password']),
            'partner_id'=>$new_part->id,
        ];
        PartnerUser::create($dataPartner);
        $dataDes = [];
        $languages = $this->languages;
        foreach ($languages as $code => $value) {
            $dataDes[] = [
                'partner_id' => $new_part->id,
                'lang'        => $code,
                'title'       => $data['descriptions'][$code]['name'],
                'keyword'     => $data['descriptions'][$code]['keyword'],
                'description' => $data['descriptions'][$code]['description'],
            ];
        }
        AdminPartner::insertDescription($dataDes);
        return  redirect()->back()->with('success',au_language_render('admin.partner_created'));
    }

    public function show($id)
    {
        $partner = FegguPartner::find($id);
        if (!$partner) {
            $data = [
                'title' => au_language_render('feggu.admin.title'),
                'subTitle' => '',
                'icon' => 'fas fa-cogs',
                'dataNotFound' => 1
            ];
            return view($this->templatePathAdmin.'screen.feggu_info')
                ->with($data);
        }
        $data = [
            'title' => au_language_render('feggu.admin.title_detail_partner'),
            'subTitle' => '',
            'icon' => 'fas fa-cogs',
            'partner'=>$partner,
            'partnerId'=>$id,
            'users'=>$partner->users,
            'patients'=>$partner->patients,
        ];


        return view($this->templatePathAdmin.'screen.partner_detail')
            ->with($data);
    }
}
