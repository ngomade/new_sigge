<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\concours\SessionConcours;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        if (Session::has('user')) {
            return view('concours.frontend.index_connexion')->render();
        } else {
            $currentYear = (new DateTime)->format('Y');
            $session = SessionConcours::where('annee', $currentYear)->first();
            $startDate = Carbon::parse($session->debut);
            $endDate = Carbon::parse($session->cloture);
            $dateToCheck = Carbon::today();
            if ($dateToCheck->between($startDate, $endDate)) {
                return view('concours.frontend.index');
            } else {
                Session::flash('errors', "Cette session est déja fermée ou n'est pas encore ouverte.");

                return redirect('/');
            }
        }

    }
}
