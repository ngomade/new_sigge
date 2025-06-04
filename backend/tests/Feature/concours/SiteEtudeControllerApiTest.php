<?php

namespace Tests\Feature\concours;

use App\Models\concours\SiteEtude;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteEtudeControllerApiTest extends TestCase
{
    use RefreshDatabase;
    protected $user;



    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['usertype' => 'admin']);
    }

    public function test_can_list_sites()
    {
        SiteEtude::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->getJson('/api/concours/sites');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_site()
    {
        $siteData = [
            'label_site' => 'Test Site',
            'description_site' => 'Test Description'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/concours/sites', $siteData);

        $response->assertStatus(201)
            ->assertJsonFragment($siteData);

        $this->assertDatabaseHas('site_etude', $siteData);
    }

    public function test_can_show_site()
    {
        $site = SiteEtude::factory()->create();

        $response = $this->actingAs($this->user)
            ->getJson("/api/concours/sites$site->code_site");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'label_site' => $site->label_site,
                'description_site' => $site->description_site
            ]);
    }

    public function test_can_update_site()
    {
        $site = SiteEtude::factory()->create();
        $updateData = [
            'label_site' => 'Updated Site',
            'description_site' => 'Updated Description'
        ];

        $response = $this->actingAs($this->user)
            ->putJson("/api/concours/sites$site->code_site", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('site_etude', $updateData);
    }

    public function test_can_delete_site()
    {
        $site = SiteEtude::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/concours/sites/$site->code_site");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('site_etude', ['code_site' => $site->code_site]);
    }

    public function test_can_get_site_statistics()
    {
        $site = SiteEtude::factory()->create();

        $response = $this->actingAs($this->user)
            ->getJson("/api/concours/sites/$site->code_site/stats");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'site',
                'total_candidats',
                'candidats_par_sexe',
                'candidats_par_filiere',
                'candidats_par_nationalite'
            ]);
    }

    public function test_can_search_sites()
    {
        SiteEtude::factory()->create(['label_site' => 'Test Site']);
        SiteEtude::factory()->create(['label_site' => 'Another Site']);

        $response = $this->actingAs($this->user)
            ->postJson('/api/concours/sites/search', ['label' => 'Test']);

        $response->assertStatus(201)
            ->assertJsonCount(1);
    }
}
