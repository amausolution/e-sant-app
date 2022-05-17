<?php

namespace Feggu\Core\Partner\Controllers;

use App\Http\Controllers\RootPartnerController;
use Feggu\Core\Admin\Models\AdminNews;
use Feggu\Core\Admin\Models\AdminCustomer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardPartnerController extends RootPartnerController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        return Inertia::render('Partner/Dashboard');
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
        return view($this->templatePathPartner.'data_not_found', $data);
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
        return view($this->templatePathPartner.'deny', $data);
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
        return view($this->templatePathPartner.'deny_single', $data);
    }
}
