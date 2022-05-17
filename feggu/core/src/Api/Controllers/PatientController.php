<?php

namespace Feggu\Core\Api\Controllers;

use App\Http\Controllers\RootFrontController;
use Illuminate\Http\Request;
use Feggu\Core\Partner\Models\ShopOrder;

class PatientController extends RootFrontController
{

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function getInfo(Request $request)
    {
        return response()->json($request->user());
    }
}
