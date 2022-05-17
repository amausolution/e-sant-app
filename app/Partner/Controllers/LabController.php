<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RootPartnerController;
use Illuminate\Http\Request;

class LabController extends RootPartnerController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }

    public function index() {
        return view($this->plugin->pathPlugin.'::Partner',
            [
                //
            ]
        );
    }
}
