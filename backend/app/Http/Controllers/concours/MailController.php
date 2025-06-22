<?php

namespace App\Http\Controllers\concours;

use App\Models\Mails;
use App\Models\Candidat;
use Illuminate\Http\Request;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mails = Mails::all();
        return view("concours.backend.send_mail", compact("mails"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $cand = Candidat::all();
            $cpt = 0;
            foreach($cand as $c){
                $cpt++;
                Mail::to($c->ca_email)
                ->send(new NotificationMail($c, $request->all()));
            }
            $res = Mails::create(array_merge($request->all(),[
                'mail_nb'   => $cpt
            ]));
            DB::commit();
        $success = $cpt." mails ont été envoyés";
        $request->session()->flash('success', $success);
        return redirect()->route('index_admin_concours');
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function supprimer(Request $request)
    {
        DB::beginTransaction();
        try {
            Mails::find($request->id_session)->delete();
            DB::commit();
            $request->session()->flash('success', "Mail Supprimé avec success");
            return back();
        } catch (\Throwable $th) {
            dd($th);
            $request->session()->flash('errors', "Echec de l'opération " . $th);
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
