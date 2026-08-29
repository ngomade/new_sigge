<?php

namespace Tests\Feature\concours;

use App\Models\concours\Candidat;
use App\Models\concours\Personnel;
use App\Models\concours\SessionConcours;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

// class SessionConcourTest extends TestCase
// {
//     use RefreshDatabase;

//     /** @test */
//     public function can_create_session_concour()
//     {
//         $personnel = Personnel::factory()->create();
//         Sanctum::actingAs($personnel);

//         $response = $this->postJson('/api/concours/sessions', [
//             'code_pers' => $personnel->code_pers,
//             'annee' => '2024-01-01',
//             'debut' => '2024-06-01',
//             'cloture' => '2024-08-31',
//         ]);

//         $response->assertStatus(201)
//             ->assertJsonStructure(['id', 'annee', 'debut', 'cloture']);
//     }

//     /** @test */
//     public function can_get_active_session()
//     {
//         $activeSession = SessionConcours::factory()->create([
//             'debut' => now()->subDays(10),
//             'cloture' => now()->addDays(10),
//         ]);

//         SessionConcours::factory()->create([
//             'debut' => now()->subMonths(3),
//             'cloture' => now()->subMonths(2),
//         ]);

//         $response = $this->getJson('/api/concours/sessions/active');

//         $response->assertStatus(200)
//             ->assertJsonPath('id', $activeSession->id);
//     }

//     /** @test */
//     public function can_get_session_statistics()
//     {
//         $session = SessionConcours::factory()->create();

//         // Create candidats for this session
//         Candidat::factory()->count(10)->create([
//             'id' => $session->id,
//             'ca_sexe' => 'M',
//         ]);

//         Candidat::factory()->count(5)->create([
//             'id' => $session->id,
//             'ca_sexe' => 'F',
//         ]);

//         $personnel = Personnel::factory()->create();
//         Sanctum::actingAs($personnel);

//         $response = $this->getJson("/api/concours/sessions/$session->id/stats");

//         $response->assertStatus(200)
//             ->assertJsonStructure([
//                 'total_candidats',
//                 'candidats_par_sexe',
//                 'candidats_par_filiere',
//                 'candidats_par_site',
//             ])
//             ->assertJsonPath('total_candidats', 15);
//     }
// }
