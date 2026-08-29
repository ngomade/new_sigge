<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ListeEtudiantExport implements FromView
{
    protected $data;

    protected $fil;

    public function __construct($donnees, $fil)
    {
        $this->data = $donnees;
        $this->fil = $fil;
    }

    public function view(): View
    {
        return view('sige_app.backend.excel.liste_etudiant', ['users' => $this->data, 'fil' => $this->fil]);
    }
}
