<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed();

        $admin = User::query()->where('role', 'super_admin')->first();
        if ($admin) {
            $this->actingAs($admin);
        }

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sistem Pendukung Keputusan Penempatan Personel Kepolisian');
        $response->assertSee('POLRI PRESISI');
    }
}
