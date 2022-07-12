<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RootPartnerController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LabController extends RootPartnerController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();

    }

    public function index() {
        return view($this->plugin->pathPlugin.'::Partner',
            [
                //
            ]
        );
    }
}
