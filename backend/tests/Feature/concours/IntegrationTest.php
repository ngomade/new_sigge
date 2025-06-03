<?php

namespace Tests\Feature\Concours;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\concours\{Compte, Candidat, Personnel, Filiere, SiteEtude, Sessionconcour, Ecole};
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;

class IntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function complete_candidat_registration_flow()
    {
        // 1. Create required data
        $filiere = Filiere::factory()->create();
        $site = SiteEtude::factory()->create();
        $session = Sessionconcour::factory()->create([
            'debut' => now()->subDays(5),
            'cloture' => now()->addDays(30)
        ]);
        $ecole = Ecole::factory()->create();

        // 2. Create compte
        $response = $this->postJson('/api/concours/comptes', [
            'ca_num_recu' => 'RECU2024TEST',
            'ca_pwd' => 'password123',
            'ca_recu' => UploadedFile::fake()->create('recu.pdf', 1000),
            'ca_nom' => 'Test',
            'ca_email' => 'test@example.com',
            'ca_prenom' => 'User'
        ]);

        $response->assertStatus(201);
        $token = $response->json('access_token');

        // 3. Check authentication
        $response = $this->getJson('/api/concours/check-token', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        // 4. Create candidat profile (as admin)
        $personnel = Personnel::factory()->create();
        Sanctum::actingAs($personnel);

        $response = $this->postJson('/api/concours/candidat', [
            'ca_code' => 'CAND2024TEST',
            'filiere_code' => $filiere->filiere_code,
            'code_site' => $site->code_site,
            'id' => $session->id,
            'ca_nom' => 'Test',
            'ca_prenom' => 'User',
            'ca_sexe' => 'M',
            'ca_date_naiss' => '1995-01-01',
            'ca_lieu_naiss' => 'Douala',
            'ca_statut_mat' => 'Célibataire',
            'ca_telephone' => '699999999',
            'ca_num_cni' => '999999999',
            'ca_email' => 'test@example.com',
            'ca_premiere_lang' => 'Français',
            'ca_nationalite' => 'Camerounaise',
            'ca_region_origine' => 'Littoral',
            'ca_depart_origine' => 'Wouri',
            'ca_diplome_admission' => 'Baccalauréat',
            'ca_annee_diplome' => 2020,
            'ca_serie_diplome' => 'C',
            'ca_mention_diplome' => 'Bien',
            'ca_etab_diplome' => 'Lycée Test',
            'ca_pays_diplome' => 'Cameroun',
            'ca_centre_examen' => 'Douala',
            'ca_centre_depot' => 'Douala',
            'ca_nom_pere' => 'Pere Test',
            'ca_telephone_pere' => '697777777',
            'ca_nom_mere' => 'Mere Test',
            'ca_telephone_mere' => '698888888',
            'ca_handicap' => 'Aucun',
            'ca_deliv_cni' => '2020-01-01',
            'ca_num_recu' => 'RECU2024TEST',
            'ca_recu' => 'path/to/recu.pdf',
            'ecoles' => [$ecole->code_ecole]
        ]);

        $response->assertStatus(201);

        // 5. Update compte with candidat code
        $candidat = Candidat::where('ca_email', 'test@example.com')->first();

        $response = $this->putJson('/api/concours/comptes/RECU2024TEST', [
            'ca_code' => $candidat->ca_code
        ]);

        $response->assertStatus(200);

        // 6. Verify complete registration
        $this->assertDatabaseHas('compte', [
            'ca_num_recu' => 'RECU2024TEST',
            'ca_code' => 'CAND2024TEST'
        ]);

        $this->assertDatabaseHas('candidat', [
            'ca_code' => 'CAND2024TEST',
            'ca_email' => 'test@example.com'
        ]);

        // 7. Verify candidat can see their information
        $compte = Compte::where('ca_num_recu', 'RECU2024TEST')->first();
        Sanctum::actingAs($compte);

        $response = $this->getJson('/api/concours/candidat/CAND2024TEST');
        $response->assertStatus(200)
            ->assertJsonPath('ca_email', 'test@example.com');
    }
}
