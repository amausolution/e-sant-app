<?php

namespace Feggu\Core\Admin\Controllers;

use App\Http\Controllers\RootAdminController;
use Feggu\Core\Admin\Models\AdminNews;
use Feggu\Core\Admin\Models\AdminCustomer;
use Illuminate\Http\Request;

class DashboardController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        //Check user allow view dasdboard
        if (!\Admin::user()->checkUrlAllowAccess(route('admin.home'))) {
            $data['title'] = au_language_render('admin.dashboard');
            return view($this->templatePathAdmin.'default', $data);
        }
        $data = [];
        return view($this->templatePathAdmin.'dashboard', $data);
    }

    /**
     * Page not found
     *
     * @return  [type]  [return description]
     */
    public function dataNotFound()
    {
        $data = [
            'title' => au_language_render('admin.data_not_found'),
            'icon' => '',
            'url' => session('url'),
        ];
        return view($this->templatePathAdmin.'data_not_found', $data);
    }


    /**
     * Page deny
     *
     * @return  [type]  [return description]
     */
    public function deny()
    {
        $data = [
            'title' => au_language_render('admin.deny'),
            'icon' => '',
            'method' => session('method'),
            'url' => session('url'),
        ];
        return view($this->templatePathAdmin.'deny', $data);
    }

    /**
     * [denySingle description]
     *
     * @return  [type]  [return description]
     */
    public function denySingle()
    {
        $data = [
            'method' => session('method'),
            'url' => session('url'),
        ];
        return view($this->templatePathAdmin.'deny_single', $data);
    }
}
