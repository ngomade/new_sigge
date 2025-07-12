<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LaboratoireInvitation extends Model
{
    protected $table = 'laboratoire_invitations';

    protected $fillable = [
        'code_lab',
        'token',
        'id_rl',
        'date_fin_affectation',
        'date_expiration',
        'statut',
        'nombre_utilisations_max',
        'nombre_utilisations_actuelles',
        'created_by'
    ];

    protected $casts = [
        'date_fin_affectation' => 'date',
        'date_expiration' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relations
    public function laboratoire(): BelongsTo
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }

    public function roleLabo(): BelongsTo
    {
        return $this->belongsTo(RoleLabo::class, 'id_rl', 'id_rl');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(PersLab::class, 'created_by', 'id_pers_lab');
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeNonExpire($query)
    {
        return $query->where('date_expiration', '>', now());
    }

    public function scopeValide($query)
    {
        return $query->where('statut', 'actif')
                    ->where('date_expiration', '>', now());
    }

    // Accesseurs
    public function getUrlInvitationAttribute(): string
    {
        // Utiliser une URL courte et fiable pour les QR codes
        $baseUrl = config('app.url');
        return rtrim($baseUrl, '/') . '/i/' . $this->token;
    }

    public function getUrlInvitationCompleteAttribute(): string
    {
        // URL complète pour l'affichage
        return url(route('laboratoires.invitation.accepter', $this->token, false));
    }

    public function getEstExpireAttribute(): bool
    {
        return $this->date_expiration < now();
    }

        public function getEstValideAttribute(): bool
    {
        return $this->statut === 'actif' &&
               !$this->est_expire &&
               $this->nombre_utilisations_actuelles < $this->nombre_utilisations_max;
    }

    public function getEstLimiteAtteinteAttribute(): bool
    {
        return $this->nombre_utilisations_actuelles >= $this->nombre_utilisations_max;
    }

    public function getQrCodeAttribute(): string
    {
        return QrCode::size(200)
            ->format('svg')
            ->style('square') // Style plus simple pour une meilleure lisibilité
            ->eye('square')   // Yeux carrés pour plus de clarté
            ->margin(2)       // Marge légèrement plus grande
            ->errorCorrection('H') // Correction d'erreur maximale
            ->color(0, 0, 0)  // Noir pour un meilleur contraste
            ->generate($this->url_invitation);
    }

    public function getQrCodePngAttribute(): string
    {
        return QrCode::size(200)
            ->format('png')
            ->style('square') // Style plus simple pour une meilleure lisibilité
            ->eye('square')   // Yeux carrés pour plus de clarté
            ->margin(2)       // Marge légèrement plus grande
            ->errorCorrection('H') // Correction d'erreur maximale
            ->color(0, 0, 0)  // Noir pour un meilleur contraste
            ->generate($this->url_invitation);
    }

    // Méthodes statiques
    public static function genererToken(): string
    {
        do {
            $token = Str::random(32);
        } while (static::where('token', $token)->exists());

        return $token;
    }

    public static function creerInvitation(
        string $code_lab,
        string $created_by,
        ?int $id_rl = null,
        ?string $date_fin_affectation = null,
        int $duree_validite_jours = 7,
        int $nombre_utilisations_max = 1
    ): self {
        return static::create([
            'code_lab' => $code_lab,
            'token' => static::genererToken(),
            'id_rl' => $id_rl,
            'date_fin_affectation' => $date_fin_affectation ?? now()->addYear(),
            'date_expiration' => now()->addDays($duree_validite_jours),
            'statut' => 'actif',
            'nombre_utilisations_max' => $nombre_utilisations_max,
            'nombre_utilisations_actuelles' => 0,
            'created_by' => $created_by
        ]);
    }

    public function marquerCommeUtilisee(): void
    {
        $this->increment('nombre_utilisations_actuelles');

        // Si la limite est atteinte, marquer comme utilisé
        if ($this->nombre_utilisations_actuelles >= $this->nombre_utilisations_max) {
            $this->update(['statut' => 'utilise']);
        }
    }

    public function marquerCommeExpiree(): void
    {
        $this->update(['statut' => 'expire']);
    }
}
