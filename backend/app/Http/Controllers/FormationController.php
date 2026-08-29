<?php

namespace App\Http\Controllers;

class FormationController extends Controller
{
    public function index()
    {
        return view('sige_app.frontend.formations.planche');
    }

    public function show_GLTCO()
    {
        return view('sige_app.frontend.formations.formation_gltco');
    }

    public function show_site()
    {
        return view('concours.frontend.show_site');
    }

    public function create()
    {
        return view('sige_app.frontend.formations.formation_ebttl');
    }
}
