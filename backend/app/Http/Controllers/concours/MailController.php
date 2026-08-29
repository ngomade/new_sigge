<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Mail\NotificationMail;
use App\Models\concours\Candidat;
use App\Models\Mails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class MailController extends Controller
{
    public function index()
    {
        $mails = Mails::all();

        return view('concours.backend.send_mail', compact('mails'));
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $cand = Candidat::all();
            $cpt = 0;
            foreach ($cand as $c) {
                $cpt++;
                Mail::to($c->ca_email)
                    ->send(new NotificationMail($c, $request->all()));
            }
            $res = Mails::create(array_merge($request->all(), [
                'mail_nb' => $cpt,
            ]));
            DB::commit();
            $success = $cpt.' mails ont été envoyés';
            $request->session()->flash('success', $success);

            return redirect()->route('index_admin_concours');
        } catch (Throwable $th) {
            DB::rollback();
        }
    }

    public function supprimer(Request $request)
    {
        try {
            DB::beginTransaction();
            Mails::find($request->id_session)->delete();
            DB::commit();
            $request->session()->flash('success', 'Mail Supprimé avec success');

            return back();
        } catch (Throwable $th) {
            $request->session()->flash('errors', "Echec de l'opération ".$th);

            return back();
        }
    }
}
