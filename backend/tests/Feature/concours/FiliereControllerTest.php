<?php

namespace Tests\Feature\concours;

use App\Models\concours\Filiere;
use App\Models\concours\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FiliereControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un utilisateur et l'authentifier
        $user = User::factory()->create([
            'usertype' => 'admin'
        ]);
        Sanctum::actingAs($user);
    }

    public function test_can_get_all_filieres()
    {
        // Créer quelques filières
        Filiere::factory()->count(3)->create();

        // Faire la requête GET
        $response = $this->getJson('/api/concours/filiere');

        // Vérifier la réponse
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'filiere_code',
                            'filiere_label',
                            'filiere_description',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
    }

    public function test_can_create_filiere()
    {
        $filiereData = [
            'filiere_code' => 'FIL0001',
            'filiere_label' => 'Génie Informatique',
            'filiere_description' => 'Description de la filière informatique'
        ];

        $response = $this->postJson('/api/concours/filiere', $filiereData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'filiere_code',
                        'filiere_label',
                        'filiere_description',
                        'created_at',
                        'updated_at'
                    ]
                ]);

        $this->assertDatabaseHas('filiere', [
            'filiere_code' => 'FIL0001',
            'filiere_label' => 'Génie Informatique'
        ]);
    }

    public function test_can_show_filiere()
    {
        $filiere = Filiere::factory()->create();

        $response = $this->getJson("/api/concours/filiere/$filiere->filiere_code");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'filiere_code',
                        'filiere_label',
                        'filiere_description',
                        'created_at',
                        'updated_at'
                    ]
                ]);
    }

    public function test_can_update_filiere()
    {
        $filiere = Filiere::factory()->create();
        $updateData = [
            'filiere_label' => 'Génie Informatique Mise à jour',
            'filiere_description' => 'Description mise à jour'
        ];

        $response = $this->putJson("/api/concours/filiere/$filiere->filiere_code", $updateData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'filiere_code',
                        'filiere_label',
                        'filiere_description',
                        'created_at',
                        'updated_at'
                    ]
                ]);

        $this->assertDatabaseHas('filiere', [
            'filiere_code' => $filiere->filiere_code,
            'filiere_label' => 'Génie Informatique Mise à jour'
        ]);
    }

    public function test_can_delete_filiere()
    {
        $filiere = Filiere::factory()->create();

        $response = $this->deleteJson("/api/concours/filiere/$filiere->filiere_code");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('filiere', [
            'filiere_code' => $filiere->filiere_code
        ]);
    }

    public function test_cannot_create_filiere_with_duplicate_code()
    {
        $existingFiliere = Filiere::factory()->create();

        $filiereData = [
            'filiere_code' => $existingFiliere->filiere_code,
            'filiere_label' => 'Génie Informatique',
            'filiere_description' => 'Description de la filière informatique'
        ];

        $response = $this->postJson('/api/concours/filiere', $filiereData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['filiere_code']);
    }

    public function test_cannot_update_filiere_with_duplicate_code()
    {
        $filiere1 = Filiere::factory()->create();
        $filiere2 = Filiere::factory()->create();

        $updateData = [
            'filiere_code' => $filiere2->filiere_code
        ];

        $response = $this->putJson("/api/concours/filiere/$filiere1->filiere_code", $updateData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['filiere_code']);
    }
}
