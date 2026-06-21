<?php

namespace Tests\Feature\Admin;

use App\Models\Grado;
use App\Models\Seccion;
use App\Models\User;
use Database\Seeders\DatosBaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocenteManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatosBaseSeeder::class);
    }

    public function test_admin_can_view_docentes_index(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
             ->get(route('admin.docentes.index'))
             ->assertOk();
    }

    public function test_non_admin_cannot_access_admin_docentes(): void
    {
        $docente = User::factory()->create();

        $this->actingAs($docente)
             ->get(route('admin.docentes.index'))
             ->assertRedirect(route('docente.dashboard'));
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.docentes.index'))
             ->assertRedirect(route('login'));
    }

    public function test_admin_can_create_docente_with_asignaciones(): void
    {
        $admin   = User::factory()->admin()->create();
        $grado   = Grado::where('nombre', '1ro')->whereHas('nivel', fn ($q) => $q->where('nombre', 'Primaria'))->first();
        $seccion = Seccion::where('nombre', 'Única')->first();

        $response = $this->actingAs($admin)->post(route('admin.docentes.store'), [
            'nombre'   => 'Maria',
            'apellido' => 'Garcia',
            'dni'      => '45678912',
            'username' => 'MGARCIA',
            'password' => '12345678',
            'asignaciones' => [
                ['curso_nombre' => 'Matemática', 'grado_id' => $grado->id, 'seccion_id' => $seccion->id],
            ],
        ]);

        $response->assertRedirect(route('admin.docentes.index'));

        $docente = User::where('username', 'MGARCIA')->first();
        $this->assertNotNull($docente);
        $this->assertSame('docente', $docente->rol);
        $this->assertSame('12345678', $docente->password_plain);
        $this->assertCount(1, $docente->asignaciones);
    }

    public function test_password_plain_is_stored_encrypted_in_database(): void
    {
        $admin   = User::factory()->admin()->create();
        $grado   = Grado::first();
        $seccion = Seccion::first();

        $this->actingAs($admin)->post(route('admin.docentes.store'), [
            'nombre'   => 'Juan',
            'apellido' => 'Perez',
            'dni'      => '78912345',
            'username' => 'JPEREZ',
            'password' => 'abcd1234',
            'asignaciones' => [
                ['curso_nombre' => 'Comunicación', 'grado_id' => $grado->id, 'seccion_id' => $seccion->id],
            ],
        ]);

        $rawValue = \Illuminate\Support\Facades\DB::table('users')
            ->where('username', 'JPEREZ')
            ->value('password_plain');

        // El valor crudo en la columna no debe ser el password en texto plano
        $this->assertNotSame('abcd1234', $rawValue);

        // Pero a través del modelo (cast 'encrypted') se descifra correctamente
        $docente = User::where('username', 'JPEREZ')->first();
        $this->assertSame('abcd1234', $docente->password_plain);
    }

    public function test_cannot_create_docente_with_duplicate_dni(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['dni' => '11223344']);

        $response = $this->actingAs($admin)->post(route('admin.docentes.store'), [
            'nombre'   => 'Otro',
            'apellido' => 'Docente',
            'dni'      => '11223344',
            'username' => 'OTRODOC',
            'password' => '12345678',
        ]);

        $response->assertSessionHasErrors('dni');
    }
}
