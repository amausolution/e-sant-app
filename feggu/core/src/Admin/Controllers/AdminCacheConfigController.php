<?php
namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Admin\Models\AdminConfig;

class AdminCacheConfigController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = [
            'title' => au_language_render('admin.cache.title'),
            'subTitle' => '',
            'icon' => 'fa fa-indent',        ];
        $configs = AdminConfig::getListConfigByCode(['code' => 'cache']);
        $data['configs'] = $configs;
        $data['urlUpdateConfigGlobal'] = au_route_partner('admin_config_global.update');
        return view($this->templatePathAdmin.'screen.cache_config')
            ->with($data);
    }

    /**
     * Clear cache
     *
     * @return  json
     */
    public function clearCache()
    {
        $action = request('action');
        $response = au_clear_cache($action);
        return response()->json(
            $response
        );
    }
}
