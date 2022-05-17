<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Partner\Models\FegguLanguage;
use Feggu\Core\Partner\Models\FegguCurrency;
use Feggu\Core\Admin\Models\AdminConfig;
use Feggu\Core\Admin\Models\AdminTemplate;
use Feggu\Core\Admin\Models\AdminPage;
use Feggu\Core\Partner\Models\ShopTax;

class AdminPartnerConfigController extends RootAdminController
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

    public function index()
    {
        $id = session('adminPartnerId');
        $data = [
            'title' => au_language_render('admin.menu_titles.config_store_default'),
            'subTitle' => '',
            'icon' => 'fas fa-cogs',
        ];

        // Customer config
        $dataCustomerConfig = [
            'code' => 'customer_config_attribute',
            'partnerId' => $id,
            'keyBy' => 'key',
        ];
        $customerConfigs = AdminConfig::getListConfigByCode($dataCustomerConfig);

        $dataCustomerConfigRequired = [
            'code' => 'customer_config_attribute_required',
            'partnerId' => $id,
            'keyBy' => 'key',
        ];
        $customerConfigsRequired = AdminConfig::getListConfigByCode($dataCustomerConfigRequired);
        //End customer

        $orderConfigQuery = [
            'code' => 'order_config',
            'partnerId' => $id,
            'keyBy' => 'key',
        ];
        $orderConfig = AdminConfig::getListConfigByCode($orderConfigQuery);

        $configDisplayQuery = [
            'code' => 'display_config',
            'partnerId' => $id,
            'keyBy' => 'key',
        ];
        $configDisplay = AdminConfig::getListConfigByCode($configDisplayQuery);

        $configCaptchaQuery = [
            'code' => 'captcha_config',
            'partnerId' => $id,
            'keyBy' => 'key',
        ];
        $configCaptcha = AdminConfig::getListConfigByCode($configCaptchaQuery);

        $configCustomizeQuery = [
            'code' => 'admin_custom_config',
            'partnerId' => $id,
            'keyBy' => 'key',
        ];
        $configCustomize = AdminConfig::getListConfigByCode($configCustomizeQuery);


        $configLayoutQuery = [
            'code' => 'config_layout',
            'partnerId' => $id,
            'keyBy' => 'key',
        ];
        $configLayout = AdminConfig::getListConfigByCode($configLayoutQuery);

        $emailConfigQuery = [
            'code' => ['smtp_config', 'email_action'],
            'partnerId' => $id,
            'groupBy' => 'code',
            'sort'    => 'asc',
        ];
        $emailConfig = AdminConfig::getListConfigByCode($emailConfigQuery);

        $data['emailConfig'] = $emailConfig;
        $data['smtp_method'] = ['' => 'None Secirity', 'TLS' => 'TLS', 'SSL' => 'SSL'];
        $data['captcha_page'] = [
            'register' => au_language_render('admin.captcha.captcha_page_register'),
            'forgot'   => au_language_render('admin.captcha.captcha_page_forgot_password'),
            'contact'  => au_language_render('admin.captcha.captcha_page_contact'),
            'review'   => au_language_render('admin.captcha.captcha_page_review'),
        ];
        if (au_config_global('MultiPartnerPro') || au_config_global('MultiStorePro')) {
            $pageList = (new AdminPage)->getListPageAlias($id);
        } else {
            $pageList = (new AdminPage)->getListPageAlias();
        }
        //End email
        $data['customerConfigs']                = $customerConfigs;
        $data['customerConfigsRequired']        = $customerConfigsRequired;
        $data['configLayout']                   = $configLayout;
        $data['pluginCaptchaInstalled']         = au_get_plugin_captcha_installed();
        $data['pageList']                       = $pageList;
        $data['configDisplay']                  = $configDisplay;
        $data['orderConfig']                    = $orderConfig;
        $data['configCaptcha']                  = $configCaptcha;
        $data['configCustomize']                = $configCustomize;
        $data['templates']                      = $this->templates;
        $data['timezones']                      = $this->timezones;
        $data['languages']                      = $this->languages;
        $data['currencies']                     = $this->currencies;
        $data['partnerId']                        = $id;
        $data['urlUpdateConfig']                = au_route_partner('admin_config.update');
        $data['urlUpdateConfigGlobal']          = au_route_partner('admin_config_global.update');

        return view($this->templatePathAdmin.'screen.config_partner_default')
        ->with($data);
    }

    /*
    Update value config store
    */
    public function update()
    {
        $data = request()->all();
        $name = $data['name'];
        $value = $data['value'];
        $partnerId = $data['partnerId'] ?? '';
        if (!$partnerId) {
            return response()->json(
                [
                'error' => 1,
                'field' => 'partnerId',
                'value' => $partnerId,
                'msg'   => 'Store ID can not empty!',
                ]
            );
        }

        try {
            AdminConfig::where('key', $name)
                ->where('partner_id', $partnerId)
                ->update(['value' => $value]);
            $error = 0;
            $msg = au_language_render('action.update_success');
        } catch (\Throwable $e) {
            $error = 1;
            $msg = $e->getMessage();
        }
        return response()->json(
            [
            'error' => $error,
            'field' => $name,
            'value' => $value,
            'msg'   => $msg,
            ]
        );
    }
}
