<?php

namespace App\Partner\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RootPartnerController;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegisterPatientController extends RootPartnerController
{
    public function index()
    {

    }
    public function create()
    {
        return Inertia::render('Partner/Patient/Create',[
            'title'=> __('Register new user'),
            'genders'=>genders(),
            'img_recto'=>asset('images/recto.png'),
            'img_verso'=>asset('images/verso.png'),
            'typePiece'=>typePiece(),
        ]);
    }

    public function store()
    {

    }
    public function edit($id)
    {
        return Inertia::render('Partner/Patient/Edit');
    }

    public function update($id)
    {

    }
}
