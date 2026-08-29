<?php

namespace App\Http\Controllers\concours;

use App\Exports\ExportCandidat;
use App\Exports\ListeEtudiantExport;
use App\Http\Controllers\Controller;
use App\Models\concours\Candidat;
use App\Models\Users;
use Barryvdh\DomPDF\PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ImpressionController extends Controller
{
    public function show($id)
    {
        try {
            $ca = Candidat::findorfail($id);

            $diplome = null;
            $centreExamen = null;
            $centreDepot = null;

            Log::info("Generating PDF for Candidat ID: $id");
            Log::info('ca_diplome_admission: '.$ca->ca_diplome_admission);
            Log::info('ca_centre_examen: '.$ca->ca_centre_examen);
            Log::info('ca_centre_depot: '.$ca->ca_centre_depot);

            if ($ca->ca_diplome_admission) {
                $diplome = \App\Models\Diplome::find($ca->ca_diplome_admission);
                Log::info('Diplome found: '.($diplome ? $diplome->label_dip : 'null'));
            }
            if ($ca->ca_centre_examen) {
                $centreExamen = \App\Models\concours\CentreExamen::find($ca->ca_centre_examen);
                Log::info('CentreExamen found: '.($centreExamen ? $centreExamen->centre_exam_label : 'null'));
            }
            if ($ca->ca_centre_depot) {
                $centreDepot = \App\Models\CentreDepot::find($ca->ca_centre_depot);
                Log::info('CentreDepot found: '.($centreDepot ? $centreDepot->centre_depot_label : 'null'));
            }

            $pdf = PDF::loadView('concours.pdf.fiche_candidat', compact('ca', 'diplome', 'centreExamen', 'centreDepot'))->setPaper('a4');

            return $pdf->download('Fiche_'.$id.'.pdf');
        } catch (Exception $e) {
            Log::error("Error generating PDF for Candidat ID: $id - ".$e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Candidat non trouvé ou erreur lors de la génération du PDF.']);
        }
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function print($type)
    {
        $candidats = Candidat::all()->take(200);
        if ($type == 'pdf') {
            $pdf = PDF::loadView('concours.pdf.impression_liste', compact('candidats'))->setPaper('a3', 'landscape');

            return $pdf->download('Liste_Candidats');
        } else {
            return Excel::download(new ExportCandidat, 'Liste_Candidat.xlsx');
        }
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function imprimer(Request $request)
    {
        $filiere = $request->filier;
        $ecole = $request->code_sit;
        $annee = $request->code_anne;
        $niveau = $request->nivea;
        // $inscrit = $request->inscri;
        $users = Users::join('inscription', 'users.code_user', 'inscription.code_user')
            ->join('filiere_niveau', 'filiere_niveau.code_ins', 'inscription.code_ins')
            ->where('users.ecole_user', $ecole)
            ->where('inscription.code_annee', (int) $annee)
            // ->where("inscription.statut_ins", (int)$inscrit)
            ->where('filiere_niveau.code_filiere', $filiere)
            ->where('filiere_niveau.code_niveau', $niveau)
            ->orderBy('users.nom_user')
            ->get();

        // $type = explode("-", $id)[0];
        // $fil = explode("-", $id)[1];
        // $users = User::join("inscription", "users.code_user", "inscription.code_user")
        //             ->join("filiere_niveau", "inscription.code_ins", "=", 'filiere_niveau.code_ins')
        //             ->where("filiere_niveau.code_filiere", $fil)->orderBy("nom_user")->get("*");
        // if ($type == "pdf") {
        //     $pdf = PDF::loadView("sige_app.backend.pdf.liste_etudiant" , compact(["users", "fil"]));
        //     // $font = $pdf->getFontMetrics()->get_font("helvetica", "bold");
        //     // $pdf->get_canvas()->page_text(34, 18, "{PAGE_NUM} / {PAGE_COUNT}", $font, 10, array(0, 0, 0));
        //     return $pdf->download("Liste_Etudiant-".$fil);
        // } else {
        return Excel::download(new ListeEtudiantExport($users, $filiere), 'Liste_Etudiant-'.$filiere.date('dmY').'.xlsx');
        // }
    }
}
