<?php

namespace Tests\Feature\concours;

use App\Models\concours\Personnel;
use App\Models\Ecole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EcoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    /** @test */
    public function can_create_ecole_with_logo()
    {
        $centreDepot = \App\Models\CentreDepot::factory()->create();
        $personnel = Personnel::factory()->create();
        Sanctum::actingAs($personnel);

        $response = $this->postJson('/api/concours/ecole', [
            'code_ecole' => 'ECOLE001',
            'label_ecole' => 'École Polytechnique',
            'logo_ecole' => UploadedFile::fake()->image('logo.png'),
            'desc_ecole' => 'Description de l\'école',
            'tel_ecole' => '699123456',
            'email_ecole' => 'contact@ecole.cm',
            'bp_ecole' => 'BP 123 Douala',
            'centre_depot' => $centreDepot->centre_depot_code,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('ecole', [
            'code_ecole' => 'ECOLE001',
            'label_ecole' => 'École Polytechnique',
        ]);

        Storage::assertExists('private/logos');
    }

    /** @test */
    public function can_update_ecole_with_logo()
    {
        $ecole = Ecole::factory()->create();
        $personnel = Personnel::factory()->create();
        Sanctum::actingAs($personnel);

        $response = $this->putJson("/api/concours/ecole/$ecole->code_ecole", [
            'label_ecole' => 'École Supérieure',
            'logo_ecole' => UploadedFile::fake()->image('new_logo.png'),
            'desc_ecole' => 'Nouvelle description de l\'école',
            'tel_ecole' => '699654321',
            'email_ecole' => 'logan@gmail.com',
            'bp_ecole' => 'BP 456 Yaoundé',
            'centre_depot' => $ecole->centre_depot_code,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('ecole', [
            'code_ecole' => $ecole->code_ecole,
            'label_ecole' => 'École Supérieure',
        ]);
        Storage::assertExists('private/logos/new_logo.png');

        // Clean up the old logo if it exists
        if ($ecole->logo_ecole) {
            Storage::delete($ecole->logo_ecole);
        }
        Storage::assertMissing('private/logos/'.$ecole->logo_ecole);

    }

    /** @test */
    public function can_delete_ecole()
    {
        $ecole = Ecole::factory()->create();
        $personnel = Personnel::factory()->create();
        Sanctum::actingAs($personnel);

        $response = $this->deleteJson("/api/concours/ecole/$ecole->code_ecole");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('ecole', [
            'code_ecole' => $ecole->code_ecole,
        ]);
    }

    /** @test */
    public function can_list_ecoles()
    {
        Ecole::factory()->create(['label_ecole' => 'École A']);
        Ecole::factory()->create(['label_ecole' => 'École B']);
        $personnel = Personnel::factory()->create();
        Sanctum::actingAs($personnel);

        $response = $this->getJson('/api/concours/ecoles');
        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['label_ecole' => 'École A'])
            ->assertJsonFragment(['label_ecole' => 'École B']);
    }

    /** @test */
    public function can_show_ecole_details()
    {
        $ecole = Ecole::factory()->create();
        $personnel = Personnel::factory()->create();
        Sanctum::actingAs($personnel);

        $response = $this->getJson("/api/concours/ecole/$ecole->code_ecole");
        $response->assertStatus(200)
            ->assertJsonFragment(['label_ecole' => $ecole->label_ecole]);
    }

    /** @test */
    public function cannot_create_ecole_without_required_fields()
    {
        $personnel = Personnel::factory()->create();
        Sanctum::actingAs($personnel);

        $response = $this->postJson('/api/concours/ecole', [
            'code_ecole' => 'ECOLE002',
            // Missing required fields
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['label_ecole', 'logo_ecole', 'desc_ecole', 'tel_ecole', 'email_ecole', 'bp_ecole']);
    }

    /** @test */
    public function cannot_update_ecole_with_invalid_data()
    {
        $ecole = Ecole::factory()->create();
        $personnel = Personnel::factory()->create();
        Sanctum::actingAs($personnel);

        $response = $this->putJson("/api/concours/ecole/$ecole->code_ecole", [
            'label_ecole' => '', // Invalid data
            'logo_ecole' => UploadedFile::fake()->image('invalid_logo.png'),
            'desc_ecole' => 'Updated description',
            'tel_ecole' => 'invalid_phone', // Invalid phone format
            'email_ecole' => 'not-an-email', // Invalid email format
            'bp_ecole' => 'BP 789',
            'centre_depot' => $ecole->centre_depot_code,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['label_ecole', 'tel_ecole', 'email_ecole']);
    }

    /** @test */
    public function cannot_delete_non_existent_ecole()
    {
        $personnel = Personnel::factory()->create();
        Sanctum::actingAs($personnel);

        $response = $this->deleteJson('/api/concours/ecole/INVALID_CODE');
        $response->assertStatus(404)
            ->assertJson(['message' => 'Ecole not found']);
    }

    /** @test */
    public function cannot_access_ecole_management_without_authentication()
    {
        $response = $this->getJson('/api/concours/ecoles');
        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
