<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;

class AdminSeoConfigController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [
            'title'    => au_language_render('admin.seo.config'),
            'subTitle' => '',
            'icon'     => 'fa fa-indent',
        ];
        $data['urlUpdateConfigGlobal'] = au_route_partner('admin_config_global.update');
        return view($this->templatePathAdmin.'screen.seo_config')
            ->with($data);
    }
}
