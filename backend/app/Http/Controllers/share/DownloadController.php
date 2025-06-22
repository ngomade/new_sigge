<?php

namespace App\Http\Controllers\share;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Response;
use App\Http\Controllers\Controller;

class DownloadController extends Controller
{

    public function show($chemin)
    {
        if (Str::contains($chemin, 'cursus') || Str::contains($chemin, 'programmes')) {
            return Response::download(storage_path("app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."cursus".DIRECTORY_SEPARATOR.$chemin.".pdf"));
        }
        if (Str::contains($chemin, 'arrete')) {
            return Response::download(storage_path("app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."arrete".DIRECTORY_SEPARATOR.$chemin.".pdf"));
        }
        if (Str::contains($chemin, 'resultat')) {
            return Response::download(storage_path("app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."arrete".DIRECTORY_SEPARATOR.$chemin.".pdf"));
        }
        if (Str::contains($chemin, 'inscription')) {
            return Response::download(storage_path("app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."arrete".DIRECTORY_SEPARATOR.$chemin.".pdf"));
        }else{
            return Response::download(storage_path("app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."documents".DIRECTORY_SEPARATOR.$chemin.".pdf"));
        }
    }
}
