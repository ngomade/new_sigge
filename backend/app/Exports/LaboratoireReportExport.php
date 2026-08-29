<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaboratoireReportExport implements ShouldAutoSize, WithMultipleSheets
{
    protected $laboratoire;

    protected $data;

    protected $type;

    public function __construct($laboratoire, $data, $type)
    {
        $this->laboratoire = $laboratoire;
        $this->data = $data;
        $this->type = $type;
    }

    public function sheets(): array
    {
        $sheets = [];

        switch ($this->type) {
            case 'general':
                $sheets[] = new GeneralReportSheet($this->laboratoire, $this->data);
                break;
            case 'membres':
                $sheets[] = new MembresReportSheet($this->laboratoire, $this->data);
                break;
            case 'projets':
                $sheets[] = new ProjetsReportSheet($this->laboratoire, $this->data);
                break;
            case 'equipements':
                $sheets[] = new EquipementsReportSheet($this->laboratoire, $this->data);
                break;
            case 'utilisations':
                $sheets[] = new UtilisationsReportSheet($this->laboratoire, $this->data);
                break;
        }

        return $sheets;
    }
}

class GeneralReportSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $laboratoire;

    protected $data;

    public function __construct($laboratoire, $data)
    {
        $this->laboratoire = $laboratoire;
        $this->data = $data;
    }

    public function collection()
    {
        return collect([
            ['Statistique', 'Valeur'],
            ['Membres actifs', $this->data['membres']],
            ['Projets de recherche', $this->data['projets']],
            ['Équipements', $this->data['equipements']],
            ['Publications', $this->data['publications']],
            ['Utilisateurs externes', $this->data['externes']],
            ['Réservations totales', $this->data['reservations']],
        ]);
    }

    public function headings(): array
    {
        return ['Statistique', 'Valeur'];
    }

    public function title(): string
    {
        return 'Vue d\'ensemble';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

class MembresReportSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $laboratoire;

    protected $data;

    public function __construct($laboratoire, $data)
    {
        $this->laboratoire = $laboratoire;
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data['membres'];
    }

    public function headings(): array
    {
        return [
            'ID Personnel',
            'Type',
            'Rôle',
            'Date d\'affectation',
            'Date de fin',
            'Statut',
        ];
    }

    public function map($membre): array
    {
        return [
            $membre->persLab->id_pers_lab ?? 'N/A',
            $membre->persLab->type_pers_lab ?? 'N/A',
            $membre->roleLabo->nom_role ?? 'N/A',
            $membre->date_affectation,
            $membre->date_fin_affectation ?? 'En cours',
            $membre->statut,
        ];
    }

    public function title(): string
    {
        return 'Membres';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

class ProjetsReportSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $laboratoire;

    protected $data;

    public function __construct($laboratoire, $data)
    {
        $this->laboratoire = $laboratoire;
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data['projets'];
    }

    public function headings(): array
    {
        return [
            'Thème',
            'Description',
            'Date de début',
            'Date de fin',
            'Statut',
            'Budget',
            'Nombre de participants',
        ];
    }

    public function map($projet): array
    {
        return [
            $projet->theme_projet,
            $projet->description_projet,
            $projet->debut_projet,
            $projet->fin_projet,
            $projet->statut_projet,
            $projet->budget ?? 'N/A',
            $projet->participants->count(),
        ];
    }

    public function title(): string
    {
        return 'Projets';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

class EquipementsReportSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $laboratoire;

    protected $data;

    public function __construct($laboratoire, $data)
    {
        $this->laboratoire = $laboratoire;
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data['equipements'];
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Description',
            'Numéro de série',
            'Statut',
            'Date d\'acquisition',
            'Prix d\'acquisition',
            'Nombre d\'entretiens',
            'Nombre de réservations',
        ];
    }

    public function map($equipement): array
    {
        return [
            $equipement->nom_equip,
            $equipement->desc_equip,
            $equipement->ref_equip,
            $equipement->etat,
            $equipement->date_achat,
            $equipement->valeur ?? 'N/A',
            $equipement->entretiens->count(),
            $equipement->reservations->count(),
        ];
    }

    public function title(): string
    {
        return 'Équipements';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

class UtilisationsReportSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $laboratoire;

    protected $data;

    public function __construct($laboratoire, $data)
    {
        $this->laboratoire = $laboratoire;
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data['reservations'];
    }

    public function headings(): array
    {
        return [
            'Équipement',
            'Utilisateur',
            'Date de début',
            'Date de fin',
            'Durée (heures)',
            'Statut',
            'Motif',
        ];
    }

    public function map($reservation): array
    {
        $debut = \Carbon\Carbon::parse($reservation->debut_reserv);
        $fin = \Carbon\Carbon::parse($reservation->fin_reserv);
        $duree = $debut->diffInHours($fin);

        return [
            $reservation->equipement->nom_equip ?? 'N/A',
            $reservation->personnel->id_pers_lab ?? 'N/A',
            $reservation->debut_reserv,
            $reservation->fin_reserv,
            $duree,
            $reservation->statut,
            $reservation->motif ?? 'N/A',
        ];
    }

    public function title(): string
    {
        return 'Utilisations';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
