<?php

// namespace Tests\Feature\concours;

// use App\Models\concours\Candidat;
// use App\Models\concours\Filiere;
// use App\Models\concours\Personnel;
// use App\Models\concours\SessionConcours;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Laravel\Sanctum\Sanctum;
// use Tests\TestCase;

// class CandidatControllerTest extends TestCase
// {
//     use RefreshDatabase;

//     protected $filiere;

//     protected $site;

//     protected $session;

//     protected $ecole;

//     protected function setUp(): void
//     {
//         parent::setUp();
//         $this->createRequiredData();
//     }

//     private function createRequiredData(): void
//     {
//         $this->filiere = Filiere::factory()->create();
//         $this->site = \App\Models\SiteEtude::factory()->create();
//         $this->session = SessionConcours::factory()->create();
//         $this->ecole = \App\Models\Ecole::factory()->create();
//     }

//     /** @test */
//     public function can_create_candidat_with_valid_data()
//     {
//         $personnel = Personnel::factory()->create();
//         Sanctum::actingAs($personnel);

//         $response = $this->postJson('/api/concours/candidat', [
//             'ca_code' => 'CND2024001',
//             'filiere_code' => $this->filiere->filiere_code,
//             'code_site' => $this->site->code_site,
//             'id' => $this->session->id,
//             'ca_nom' => 'Doe',
//             'ca_prenom' => 'John',
//             'ca_sexe' => 'M',
//             'ca_date_naiss' => '1995-01-15',
//             'ca_lieu_naiss' => 'Douala',
//             'ca_statut_mat' => 'Célibataire',
//             'ca_telephone' => '697123456',
//             'ca_num_cni' => '123456789',
//             'ca_email' => 'john.doe@example.com',
//             'ca_premiere_lang' => 'Français',
//             'ca_nationalite' => 'Camerounaise',
//             'ca_region_origine' => 'Littoral',
//             'ca_depart_origine' => 'Wouri',
//             'ca_diplome_admission' => 'Baccalauréat',
//             'ca_annee_diplome' => 2020,
//             'ca_serie_diplome' => 'C',
//             'ca_mention_diplome' => 'Bien',
//             'ca_etab_diplome' => 'Lycée de Douala',
//             'ca_pays_diplome' => 'Cameroun',
//             'ca_centre_examen' => 'Douala',
//             'ca_centre_depot' => 'Douala',
//             'ca_nom_pere' => 'Doe Senior',
//             'ca_telephone_pere' => '697654321',
//             'ca_nom_mere' => 'Jane Doe',
//             'ca_telephone_mere' => '697789012',
//             'ca_handicap' => 'Aucun',
//             'ca_deliv_cni' => '2020-01-15',
//             'ca_num_recu' => 'RECU2024001',
//             'ca_recu' => 'path/to/recu.pdf',
//             'ecoles' => [$this->ecole->code_ecole],
//         ]);

//         $response->assertStatus(201)
//             ->assertJson([
//                 'message' => 'Candidat enregistré avec success',
//             ]);

//         $this->assertDatabaseHas('candidat', [
//             'ca_code' => 'CND2024001',
//             'ca_email' => 'john.doe@example.com',
//         ]);
//     }

//     /** @test */
//     public function can_search_candidats_by_various_criteria()
//     {
//         Candidat::factory()->create([
//             'ca_nom' => 'Searchable',
//             'ca_prenom' => 'Test',
//             'ca_email' => 'search@example.com',
//             'ca_num_cni' => '987654321',
//         ]);

//         $personnel = Personnel::factory()->create();
//         Sanctum::actingAs($personnel);

//         // Search by name
//         $response = $this->getJson('/api/concours/candidats/search?nom=Searchable');
//         $response->assertStatus(200)
//             ->assertJsonCount(1);

//         // Search by email
//         $response = $this->getJson('/api/concours/candidats/search?email=search@example.com');
//         $response->assertStatus(200)
//             ->assertJsonCount(1);

//         // Search by CNI
//         $response = $this->getJson('/api/concours/candidats/search?cni=987654321');
//         $response->assertStatus(200)
//             ->assertJsonCount(1);
//     }

//     /** @test */
//     public function can_get_candidat_statistics()
//     {
//         // Create multiple candidats with different attributes
//         Candidat::factory()->count(5)->create(['ca_sexe' => 'M']);
//         Candidat::factory()->count(3)->create(['ca_sexe' => 'F']);

//         $personnel = Personnel::factory()->create();
//         Sanctum::actingAs($personnel);

//         $response = $this->getJson(' api/concours/candidats/stats');

//         $response->assertStatus(200)
//             ->assertJsonStructure([
//                 'total_candidats',
//                 'repartition_par_sexe',
//                 'repartition_par_filiere',
//                 'repartition_par_site',
//                 'repartition_par_nationalite',
//                 'repartition_par_region',
//                 'repartition_par_diplome',
//                 'repartition_par_annee_diplome',
//                 'repartition_par_mention',
//                 'candidats_avec_handicap',
//                 'repartition_par_centre_examen',
//                 'repartition_par_centre_depot',
//                 'age_moyen',
//             ]);

//         $this->assertEquals(8, $response->json('total_candidats'));
//     }
// }
