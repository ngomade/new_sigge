<?php

namespace App\Http\Controllers;

class OrganigrammeController extends Controller
{
    public function index()
    {
        return view('sige_app.frontend.autres.organigramme');
    }

    public function create()
    {
        return view('sige_app.frontend.autres.staff_admin');
    }
}
