<?php
#app/Http/Controller/RootAdminController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class RootPartnerController extends Controller
{
    public $templatePathPartner;
    public function __construct()
    {
        $this->templatePathPartner = config('partner.path_view');
    }

}
