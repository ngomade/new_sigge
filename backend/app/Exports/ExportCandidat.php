<?php

namespace App\Exports;

use App\Models\concours\Candidat;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportCandidat implements FromCollection
{
    public function collection(): Collection|\LaravelIdea\Helper\App\Models\concours\_IH_Candidat_C|array|\Illuminate\Support\Collection
    {
        return Candidat::all();
    }
}
