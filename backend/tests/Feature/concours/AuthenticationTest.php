<?php

namespace Tests\Feature\concours;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\concours\{Compte, Personnel};

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Set up the test environment.
     *
     * @return void
     */


    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Notification::fake();
    }
    /** @test */
    public function candidat_can_login_with_valid_credentials()
    {
        Compte::factory()->create([
            'ca_num_recu' => 'TEST123',
            'ca_pwd' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/concours/auth/login', [
            'login' => 'TEST123',
            'password' => 'password123'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'user' => [
                    'ca_num_recu',
                    'ca_nom',
                    'ca_email'
                ]
            ]);
    }

   /** @test */
    public function personnel_can_login_with_valid_credentials()
    {
        Personnel::factory()->create([
            'login_pers' => 'admin123',
            'pwd_pers' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/concours/auth/login', [
            'login' => 'admin123',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'user' => [
                    'code_pers',
                    'nom_pers',
                    'email_pers'
                ]
            ]);
    }

    /** @test */
    public function login_fails_with_missing_credentials()
    {
        $response = $this->postJson('/api/concours/auth/login');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['login', 'password']);
    }
    /** @test */
    public function login_fails_with_incorrect_credentials()
    {
        $response = $this->postJson('/api/concours/auth/login', [
            'login' => 'nonexistent',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'errors' => 'Information de connexion incorrect.'
            ]);
    }
    public function login_fails_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/concours/auth/login', [
            'login' => 'invalid',
            'password' => 'wrong'
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'errors' => 'Information de connexion incorrect.'
            ]);
    }

 /** @test */
    public function authenticated_user_can_logout()
    {
        $compte = Compte::factory()->create();
        Sanctum::actingAs($compte);

        $response = $this->postJson(' api/concours/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Déconnexion réussie.',
                'status' => 'success'
            ]);
    }

  /** @test */
    public function unauthenticated_user_cannot_logout()
    {
        $response = $this->postJson(' api/concours/logout');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }
    /** @test */
    public function check_token_returns_user_info_for_authenticated_user()
    {
        $compte = Compte::factory()->create();
        Sanctum::actingAs($compte);

        $response = $this->getJson(' api/concours/check-token');

        $response->assertStatus(200)
            ->assertJsonStructure(['user']);
    }
    /** @test */
    public function check_token_returns_user_info_for_valid_token()
    {
        $compte = Compte::factory()->create();
        $token = $compte->createToken('auth_token')->plainTextToken;

        $response = $this->getJson(' api/concours/check-token', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['user']);
    }

    /** @test */
    public function refresh_token_creates_new_token()
    {
        $compte = Compte::factory()->create();
        Sanctum::actingAs($compte);

        $response = $this->postJson(' api/concours/check-token');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'message'
            ]);
    }
}
