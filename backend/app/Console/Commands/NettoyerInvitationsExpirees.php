<?php

namespace App\Console\Commands;

use App\Models\laboratoires\LaboratoireInvitation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NettoyerInvitationsExpirees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laboratoire:nettoyer-invitations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoie les invitations de laboratoire expirées';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Début du nettoyage des invitations expirées...');

        $invitationsExpirees = LaboratoireInvitation::where('date_expiration', '<', now())
            ->where('statut', 'actif')
            ->get();

        $count = 0;
        foreach ($invitationsExpirees as $invitation) {
            $invitation->marquerCommeExpiree();
            $count++;
        }

        $this->info("Nettoyage terminé. {$count} invitation(s) expirée(s) marquée(s) comme expirée(s).");

        Log::info('Nettoyage automatique des invitations expirées terminé', [
            'nombre_invitations_expirees' => $count,
        ]);

        return 0;
    }
}
