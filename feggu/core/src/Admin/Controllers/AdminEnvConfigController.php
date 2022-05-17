<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Admin\Models\AdminConfig;

class AdminEnvConfigController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [
            'title'    => au_language_render('admin.env.title'),
            'subTitle' => '',
            'icon'     => 'fa fa-indent',
        ];

        $dataCustomerConfig = [
            'code' => 'admin_dashboard',
            'partnerId' => 0,
            'keyBy' => 'key',
        ];
        $configDashboard = AdminConfig::getListConfigByCode($dataCustomerConfig);

        $data['configDashboard'] = $configDashboard;
        $data['urlUpdateConfigGlobal'] = au_route_partner('admin_config_global.update');
        return view($this->templatePathAdmin.'screen.env')
            ->with($data);
    }
}
