<?php

namespace App\Http\Controllers;

class MairieController extends Controller
{
    public function index()
    {
        return view('sige_app.frontend.mairie.mairie_present');
    }

    public function projet_mairie()
    {
        return view('sige_app.frontend.mairie.projet_mairie');
    }

    public function create()
    {
        return view('sige_app.frontend.mairie.organigramme_mairie');
    }
}
