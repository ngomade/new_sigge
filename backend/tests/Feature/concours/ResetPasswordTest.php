<?php

namespace Tests\Feature\concours;

use App\Models\concours\Compte;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    /** @test */
    public function can_request_password_reset_for_compte()
    {
        $compte = Compte::factory()->create([
            'ca_num_recu' => 'TEST123',
            'ca_email' => 'test@example.com',
        ]);

        $response = $this->postJson('/api/concours/auth/forgot-password ', [
            'login' => 'TEST123',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Un email de réinitialisation a été envoyé à votre adresse email.',
            ]);

        $compte->refresh();
        $this->assertNotNull($compte->reset_token);
        $this->assertNotNull($compte->reset_token_expires_at);
    }

    /** @test */
    public function can_reset_password_with_valid_token()
    {
        $compte = Compte::factory()->create([
            'reset_token' => '12345',
            'reset_token_expires_at' => now()->addHours(),
        ]);

        $response = $this->postJson('/api/concours/auth/reset-password', [
            'token' => '12345',
            'password' => 'newpassword123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Votre mot de passe a été réinitialisé avec succès.',
            ]);

        $compte->refresh();
        $this->assertNull($compte->reset_token);
        $this->assertTrue(Hash::check('newpassword123', $compte->ca_pwd));
    }

    /** @test */
    public function cannot_reset_password_with_expired_token()
    {
        Compte::factory()->create([
            'reset_token' => '12345',
            'reset_token_expires_at' => now()->subHours(),
        ]);

        $response = $this->postJson('/api/concours/auth/reset-password', [
            'token' => '12345',
            'password' => 'newpassword123',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Token invalide ou expiré.',
            ]);
    }
}
