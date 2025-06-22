<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrganigrammeController extends Controller
{
    public function index()
    {
        return view("sige_app.frontend.autres.organigramme");
    }

    public function create()
    {
        return view("sige_app.frontend.autres.staff_admin");
    }
}
