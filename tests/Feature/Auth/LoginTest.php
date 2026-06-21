<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_form_is_accessible(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_admin_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create(['username' => 'ADMIN1']);

        $response = $this->post('/login', [
            'username' => 'ADMIN1',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_docente_can_login_and_is_redirected_to_docente_dashboard(): void
    {
        $docente = User::factory()->create(['username' => 'DOC1']);

        $response = $this->post('/login', [
            'username' => 'DOC1',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('docente.dashboard'));
        $this->assertAuthenticatedAs($docente);
    }

    public function test_inactive_docente_cannot_login(): void
    {
        User::factory()->inactivo()->create(['username' => 'DOC2']);

        $response = $this->post('/login', [
            'username' => 'DOC2',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        User::factory()->create(['username' => 'DOC3']);

        $response = $this->post('/login', [
            'username' => 'DOC3',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }
}
