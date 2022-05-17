<?php
namespace App\Partner\Controllers\Auth;

use Feggu\Core\Partner\Models\PartnerPermission;
use Illuminate\Support\Str;
use Validator;

class PartnerPermissionController extends PartnerPermission
{

    public function __construct()
    {
        parent::__construct();

    }

}
