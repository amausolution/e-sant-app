<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardPartnerController extends Controller
{


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
        return Inertia::render('404',[
            'title' => au_language_render('admin.data_not_found'),
            'icon' => '',
            'url' => session('url'),
        ]);
    }


    /**
     * Page deny
     *
     * @return  [type]  [return description]
     */
    public function deny()
    {
        return Inertia::render('403',[
            'title' => au_language_render('admin.data_not_found'),
            'icon' => '',
            'url' => session('url'),
        ]);
    }

    /**
     * [denySingle description]
     *
     * @return  [type]  [return description]
     */
    public function denySingle()
    {
        return Inertia::render('403',[
            'title' => au_language_render('admin.data_not_found'),
            'icon' => '',
            'url' => session('url'),
        ]);
    }
}
