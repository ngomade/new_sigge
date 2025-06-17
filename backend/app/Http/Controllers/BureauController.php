<?php

namespace App\Http\Controllers;

use App\Models\Bureau;
use App\Models\notes\Document;
use App\Models\Personnel;
use App\Models\PersRole;
use App\Models\Presentation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Image;
use Throwable;

class BureauController extends Controller
{

    public function index(string $type_bureau)
    {
        return view("sige_app.backend.administration.bureau", compact("type_bureau"));
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $bureau = Bureau::create($request->all());
            DB::commit();
            if ($bureau != null) {
                $success = $request->type_bureau . " créé avec success";
                return redirect("/bureau/" . $request->type_bureau)->with(compact("success"));
            }
            return redirect()->back()->withErrors("Echec de création du " . $request->type_bureau)->withInput();
        } catch (Throwable $th) {
            return redirect()->back()->withErrors("Echec de création du " . $request->type_bureau . " " . $th)->withInput();
        }
    }

    public function store_present(Request $request)
    {

        try {
            DB::beginTransaction();
            $exist = Presentation::where("code_bureau", $request->code_bureau)->count();
            if ($exist == 0) {
                $photo_chef = $request->file('photo_chef');
                $depliant_ingenieur = $request->file('depliant_ingenieur');
                $depliant_science = $request->file('depliant_science');
                $nom_photo = "photo_" . $request->code_bureau . "." . $photo_chef->extension();
                $res = Presentation::create(array_merge($request->all(), [
                    "photo_chef" => $nom_photo
                ]));
                if ($res) {
                    $image_extension = ["png", "jpg", "gif", "bmp"];
                    $path = "/public/departements/" . $request->code_bureau . "/";
                    if (!Storage::exists($path)) {

                        Storage::makeDirectory($path, 0775, true);
                    }
                    if (($photo_chef != null) && (in_array($photo_chef->extension(), $image_extension))) {
                        $photo_chef->storeAs($path, $nom_photo);
                        $file = storage_path() . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "departements" . DIRECTORY_SEPARATOR . $request->code_bureau . DIRECTORY_SEPARATOR . $nom_photo;
                        $img = Image::make($file)->resize(300, 300, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $img->save($file);
                    }
                    DB::commit();
                    $depliant_ingenieur->storeAs($path, "depliant_ingenieur" . "." . $depliant_science->extension());
                    $depliant_science->storeAs($path, "depliant_science" . "." . $depliant_science->extension());
                    for ($i = 1; $i <= 10; $i++) {
                        $nom_fichier = "flyer_science_" . $i;
                        if ($request->file("document_" . $i) != null) {
                            $nom_fichier = "flyer_ingenieur" . $i;
                            if ($i <= 5) {
                                $request->file("document_" . $i)->storeAs($path, $nom_fichier . ".{$request->file("document_".$i)->extension()}");
                            } else {
                                $request->file("document_" . $i)->storeAs($path, $nom_fichier . ".{$request->file("document_".$i)->extension()}");
                            }
                            Document::create([
                                'code_bureau' => $request->code_bureau,
                                'label_doc' => "Flyer " . $i . " Pour le département" . $request->code_bureau,
                                'type_doc' => "Image",
                                'nom_fichier' => $nom_fichier . "." . $request->file("document_" . $i)->extension()
                            ]);
                        }
                    }
                    $success = $request->type_bureau . "Présentation mis créée avec success";
                    return redirect("/bureau/Departement")->with(compact("success"));
                }
                return redirect()->back()->withErrors("Echec de création de la présentation ")->withInput();
            } else {
                return redirect()->back()->withErrors("Cette présentation existe déja veuiller plutot la modifier")->withInput();
            }
        } catch (Throwable $th) {
            return redirect()->back()->withErrors("Echec de création  de la présentation" . $th)->withInput();
        }
    }

    public function download_grille($dept, $nom)
    {
        return Response::download(storage_path("app" . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "departements" . DIRECTORY_SEPARATOR . $dept . DIRECTORY_SEPARATOR . $nom . ".pdf"));
    }


    public function presentation_departement(string $id)
    {
        $presentation = Presentation::where("code_bureau", $id)->first();
        $bureau = Bureau::where("code_bureau", $presentation->code_bureau)->first();
        return view("sige_app.frontend.departement.presentation_departement", compact(["presentation", "bureau"]));
    }

    public function destroy(string $type_bureau, string $code_bureau)
    {
        try {
            DB::beginTransaction();
            $res = Bureau::destroy($code_bureau);
            DB::commit();
            $success = $type_bureau . " Supprimé avec success";
            return redirect("/bureau/" . $type_bureau)->with(compact(["success"]));
        } catch (Throwable $th) {
            $errors = "Echec de suppression";
            return redirect("/index_bureau")->with(compact(["errors", "type_bureau"]));
        }
    }

    /**
     * Recherche de personnel pour l'affectation
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchPersonnel(Request $request)
    {
        $search = $request->input('search', '');

        $query = Personnel::query()
            ->select('code_pers', 'nom_pers', 'prenom_pers', 'cni_pers','first_phone_pers','second_phone_pers');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nom_pers', 'LIKE', "%{$search}%")
                  ->orWhere('prenom_pers', 'LIKE', "%{$search}%")
                  ->orWhere('code_pers', 'LIKE', "%{$search}%")
                  ->orWhere('cni_pers', 'LIKE', "%{$search}%")
                  ->orWhere('first_phone_pers', 'LIKE', "%{$search}%")
                  ->orWhere('second_phone_pers', 'LIKE', "%{$search}%");
            });
        }

        $personnel = $query->limit(50)->get();

        // Formater la réponse pour correspondre à ce qu'attend le frontend
        $formattedPersonnel = $personnel->map(function($item) {
            return [
                'id' => $item->code_pers,
                'nom' => $item->nom_pers,
                'prenom' => $item->prenom_pers,
                'num_cni' => $item->cni_pers,
                'first_phone' => $item->first_phone_pers,
                'second_phone' => $item->second_phone_pers,
            ];
        });

        return response()->json($formattedPersonnel);
    }

    /**
     * Affecter du personnel à un bureau avec un rôle
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function affecterPersonnel(Request $request)
    {
        $request->validate([
            'bureau_code' => 'required|exists:bureau,code_bureau',
            'personnels' => 'required|array',
            'personnels.*.id' => 'required|exists:personnel,code_pers',
            'personnels.*.role_id' => 'required|integer|exists:roles,id',
            'personnels.*.date_fin_role' => 'nullable|date|after:today',
            'personnels.*.statut_role' => 'sometimes|integer|in:0,1'
        ]);

        try {
            DB::beginTransaction();

            $bureauCode = $request->bureau_code;
            $personnels = $request->personnels;

            // Pour chaque personnel, on gère ses rôles
            foreach ($personnels as $personnel) {
                // Vérifier si le personnel a déjà ce rôle dans ce bureau
                $existingRole = PersRole::where([
                    'code_bureau' => $bureauCode,
                    'code_pers' => $personnel['id'],
                    'code_role' => $personnel['role_id']
                ])->first();

                if ($existingRole) {
                    // Mettre à jour le rôle existant
                    $existingRole->update([
                        'date_debut_role' => now(),
                        'date_fin_role' => $personnel['date_fin_role'] ?? null,
                        'satut_role' => $personnel['statut_role'] ?? PersRole::STATUT_ACTIF
                    ]);
                } else {
                    // Créer un nouveau rôle
                    PersRole::create([
                        'code_bureau' => $bureauCode,
                        'code_pers' => $personnel['id'],
                        'code_role' => $personnel['role_id'],
                        'date_debut_role' => now(),
                        'date_fin_role' => $personnel['date_fin_role'] ?? null,
                        'satut_role' => $personnel['statut_role'] ?? PersRole::STATUT_ACTIF
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Affectation du personnel enregistrée avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'affectation du personnel: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'affectation du personnel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer le personnel d'un bureau avec leurs rôles
     *
     * @param string $code Code du bureau
     * @return JsonResponse
     */
    public function getPersonnelBureau($code)
    {
        $personnel = PersRole::with([
                'personnel' => function($query) {
                    $query->select('code_pers', 'nom_pers', 'prenom_pers', 'cni_pers', 'first_phone_pers', 'second_phone_pers');
                },
                'role' => function($query) {
                    $query->select('id', 'name');
                }
            ])
            ->where('code_bureau', $code)
            ->get(['code_bureau', 'code_pers', 'code_role', 'date_debut_role', 'date_fin_role', 'satut_role']);

        // Formater la réponse
        $formattedPersonnel = $personnel->map(function($item) {
            $statut = 'Inactif';
            if ($item->isActif()) {
                $statut = 'Actif';
            } elseif ($item->isExpire()) {
                $statut = 'Expiré';
            }

            return [
                'id' => $item->code_pers,
                'nom' => $item->personnel->nom_pers ?? 'Inconnu',
                'prenom' => $item->personnel->prenom_pers ?? '',
                'num_cni' => $item->personnel->cni_pers ?? '',
                'first_phone' => $item->personnel->first_phone_pers ?? '',
                'second_phone' => $item->personnel->second_phone_pers ?? '',
                'role_id' => $item->code_role,
                'role_libelle' => $item->role->name ?? 'Inconnu',
                'date_debut' => $item->date_debut_role,
                'date_fin' => $item->date_fin_role ? Carbon::parse($item->date_fin_role)->format('d/m/Y') : null,
                'statut' => $statut
            ];
        });

        return response()->json($formattedPersonnel);
    }

    /**
     * Désactiver un rôle d'un personnel dans un bureau
     */
    public function desactiverRole(Request $request)
    {
        $request->validate([
            'bureau_code' => 'required|exists:bureau,code_bureau',
            'personnel_id' => 'required|exists:personnel,code_pers',
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        try {
            DB::beginTransaction();

            $role = PersRole::where([
                'code_bureau' => $request->bureau_code,
                'code_pers' => $request->personnel_id,
                'code_role' => $request->role_id
            ])->firstOrFail();

            $role->update([
                'statut_role' => PersRole::STATUT_INACTIF,
                'date_fin_role' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rôle désactivé avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la désactivation du rôle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer le code du bureau par type
     */
    public function getBureauCodeByType($type)
    {
        try {
            $bureau = Bureau::where('type_bureau', $type)->first();

            if (!$bureau) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun bureau trouvé pour ce type'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'code_bureau' => $bureau->code_bureau
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du code du bureau: ' . $e->getMessage()
            ], 500);
        }
    }
}
