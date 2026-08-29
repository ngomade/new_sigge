<?php

// namespace Tests\Feature\concours;

// use App\Models\concours\Candidat;
// use App\Models\concours\Compte;
// use App\Notifications\concours\SendinfoOfConnection;
// use Exception;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Http\UploadedFile;
// use Illuminate\Support\Facades\Notification;
// use Illuminate\Support\Facades\Storage;
// use Laravel\Sanctum\Sanctum;
// use Tests\TestCase;

// class CompteManagementTest extends TestCase
// {
//     use RefreshDatabase;

//     protected function setUp(): void
//     {
//         parent::setUp();
//         Storage::fake('local');
//         Notification::fake();
//     }

//     /** @test
//      * @throws Exception
//      */
//     public function can_create_compte_with_valid_data()
//     {
//         $candidat = Candidat::factory()->create();

//         $response = $this->postJson('/api/concours/comptes', [
//             'ca_num_recu' => 'RECU2024001',
//             'ca_code' => $candidat->ca_code,
//             'ca_pwd' => 'password123',
//             'ca_recu' => UploadedFile::fake()->create('recu.pdf', 1000),
//             'ca_nom' => 'Doe',
//             'ca_email' => 'john.doe@example.com',
//             'ca_prenom' => 'John',
//         ]);

//         $response->assertStatus(201)
//             ->assertJsonStructure([
//                 'access_token',
//                 'token_type',
//                 'user',
//             ]);

//         $this->assertDatabaseHas('compte', [
//             'ca_num_recu' => 'RECU2024001',
//             'ca_email' => 'john.doe@example.com',
//         ]);

//         Notification::assertSentTo(Compte::first(), SendinfoOfConnection::class);
//     }

//     /** @test */
//     public function cannot_create_compte_with_duplicate_receipt_number()
//     {
//         Compte::factory()->create(['ca_num_recu' => 'RECU2024001']);

//         $response = $this->postJson('api/concours/comptes', [
//             'ca_num_recu' => 'RECU2024001',
//             'ca_pwd' => 'password123',
//             'ca_recu' => UploadedFile::fake()->create('recu.pdf', 1000),
//             'ca_nom' => 'Doe',
//             'ca_prenom' => 'John',
//         ]);

//         $response->assertStatus(422)
//             ->assertJsonValidationErrors(['ca_num_recu']);
//     }

//     /** @test */
//     public function can_update_compte_information()
//     {
//         $compte = Compte::factory()->create();
//         Sanctum::actingAs($compte);

//         $response = $this->putJson("/api/concours/comptes$compte->ca_num_recu", [
//             'ca_nom' => 'Updated Name',
//             'ca_email' => 'updated@example.com',
//         ]);

//         $response->assertStatus(200);

//         $this->assertDatabaseHas('compte', [
//             'ca_num_recu' => $compte->ca_num_recu,
//             'ca_nom' => 'Updated Name',
//             'ca_email' => 'updated@example.com',
//         ]);
//     }

//     /** @test */
//     public function can_download_receipt()
//     {
//         $compte = Compte::factory()->create();
//         Storage::put($compte->ca_recu, 'fake receipt content');

//         Sanctum::actingAs($compte);

//         $response = $this->get("/api/concours/comptes/download-recu/$compte->ca_num_recu");

//         $response->assertStatus(200)
//             ->assertDownload();
//     }
// }
