<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationAgent extends Model
{
    protected $table = 'reservation_agent';
    protected $primaryKey = ['code_equip', 'id_pers_lab'];
    public $incrementing = false;

    protected $fillable = [
        'code_equip',
        'id_pers_lab',
        'debut_reserv',
        'fin_reserv',
        'statut'
    ];

    protected $casts = [
        'debut_reserv' => 'date',
        'fin_reserv' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relations
    public function equipement(): BelongsTo
    {
        return $this->belongsTo(Equipements::class, 'code_equip', 'code_equip');
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(\App\Models\laboratoires\PersLab::class, 'id_pers_lab', 'id_pers_lab');
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en attente');
    }

    public function scopeConfirme($query)
    {
        return $query->where('statut', 'confirmé');
    }

    public function scopeRefuse($query)
    {
        return $query->where('statut', 'refusé');
    }

    public function scopeAnnule($query)
    {
        return $query->where('statut', 'annulé');
    }

    public function scopeActive($query)
    {
        return $query->where('debut_reserv', '<=', now())
                    ->where('fin_reserv', '>=', now());
    }

    public function scopeFutur($query)
    {
        return $query->where('debut_reserv', '>', now());
    }

    public function scopePasse($query)
    {
        return $query->where('fin_reserv', '<', now());
    }

    // Accesseurs
    public function getStatutBadgeAttribute()
    {
        return match($this->statut) {
            'en attente' => 'warning',
            'confirmé' => 'success',
            'refusé' => 'danger',
            'annulé' => 'secondary',
            default => 'info'
        };
    }

    public function getStatutLabelAttribute()
    {
        return match($this->statut) {
            'en attente' => 'En attente',
            'confirmé' => 'Confirmé',
            'refusé' => 'Refusé',
            'annulé' => 'Annulé',
            default => ucfirst($this->statut)
        };
    }

    public function getDebutFormattedAttribute()
    {
        return $this->debut_reserv ? $this->debut_reserv->format('d/m/Y') : 'Non définie';
    }

    public function getFinFormattedAttribute()
    {
        return $this->fin_reserv ? $this->fin_reserv->format('d/m/Y') : 'Non définie';
    }

    // Méthodes
    public function isEnAttente()
    {
        return $this->statut === 'en attente';
    }

    public function isConfirme()
    {
        return $this->statut === 'confirmé';
    }

    public function isRefuse()
    {
        return $this->statut === 'refusé';
    }

    public function isAnnule()
    {
        return $this->statut === 'annulé';
    }

    public function isActive()
    {
        return $this->debut_reserv <= now() && $this->fin_reserv >= now();
    }

    public function isFutur()
    {
        return $this->debut_reserv > now();
    }

    public function isPasse()
    {
        return $this->fin_reserv < now();
    }

    public function getDuree()
    {
        if (!$this->debut_reserv || !$this->fin_reserv) {
            return null;
        }

        return $this->debut_reserv->diffInDays($this->fin_reserv);
    }

    public function getDureeFormatted()
    {
        $duree = $this->getDuree();
        if ($duree === null) {
            return 'Non définie';
        }

        if ($duree === 0) {
            return '1 jour';
        }

        return $duree . ' jour(s)';
    }

    public function getJoursRestants()
    {
        if (!$this->isActive()) {
            return null;
        }

        return now()->diffInDays($this->fin_reserv);
    }

    public function getJoursRestantsFormatted()
    {
        $jours = $this->getJoursRestants();
        if ($jours === null) {
            return 'Non applicable';
        }

        if ($jours === 0) {
            return 'Dernier jour';
        }

        return $jours . ' jour(s) restant(s)';
    }
}
