<?php

namespace Tests\Feature\Docente;

use App\Models\Archivo;
use App\Models\Curso;
use App\Models\DocenteAsignacion;
use App\Models\Grado;
use App\Models\Seccion;
use App\Models\User;
use Database\Seeders\DatosBaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArchivoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatosBaseSeeder::class);
        Storage::fake('public');
    }

    private function crearAsignacion(User $docente): DocenteAsignacion
    {
        return DocenteAsignacion::create([
            'user_id'    => $docente->id,
            'curso_id'   => Curso::first()->id,
            'grado_id'   => Grado::first()->id,
            'seccion_id' => Seccion::first()->id,
        ]);
    }

    public function test_docente_can_upload_archivo(): void
    {
        $docente    = User::factory()->create();
        $asignacion = $this->crearAsignacion($docente);
        $file       = UploadedFile::fake()->create('tarea.pdf', 100, 'application/pdf');

        $response = $this->actingAs($docente)->post(route('docente.archivos.store'), [
            'archivo'       => $file,
            'bimestre'      => 1,
            'asignacion_id' => $asignacion->id,
        ]);

        $response->assertRedirect(route('docente.archivos.index'));

        $archivo = Archivo::where('user_id', $docente->id)->first();
        $this->assertNotNull($archivo);
        Storage::disk('public')->assertExists($archivo->ruta);
    }

    public function test_docente_cannot_upload_with_asignacion_from_another_docente(): void
    {
        $docente   = User::factory()->create();
        $otro      = User::factory()->create();
        $asignacion = $this->crearAsignacion($otro);
        $file      = UploadedFile::fake()->create('tarea.pdf', 100, 'application/pdf');

        $response = $this->actingAs($docente)->post(route('docente.archivos.store'), [
            'archivo'       => $file,
            'bimestre'      => 1,
            'asignacion_id' => $asignacion->id,
        ]);

        $response->assertSessionHasErrors('asignacion_id');
    }

    public function test_docente_cannot_stream_another_docentes_archivo(): void
    {
        $propietario = User::factory()->create();
        $intruso     = User::factory()->create();
        $asignacion  = $this->crearAsignacion($propietario);

        $archivo = Archivo::create([
            'user_id'         => $propietario->id,
            'nombre_original' => 'tarea.pdf',
            'nombre_archivo'  => 'doc_123.pdf',
            'ruta'            => "docentes/{$propietario->id}/doc_123.pdf",
            'tipo_archivo'    => 'application/pdf',
            'tamanio'         => 100,
            'curso_id'        => $asignacion->curso_id,
            'grado_id'        => $asignacion->grado_id,
            'seccion_id'      => $asignacion->seccion_id,
            'bimestre'        => 1,
            'anio'            => now()->year,
        ]);

        $this->actingAs($intruso)
             ->get(route('docente.archivos.stream', $archivo))
             ->assertForbidden();
    }

    public function test_stream_returns_404_when_file_missing_on_disk(): void
    {
        $docente    = User::factory()->create();
        $asignacion = $this->crearAsignacion($docente);

        // Registro en BD sin archivo físico en storage (caso real ya visto en producción)
        $archivo = Archivo::create([
            'user_id'         => $docente->id,
            'nombre_original' => 'perdido.pdf',
            'nombre_archivo'  => 'doc_999.pdf',
            'ruta'            => "docentes/{$docente->id}/doc_999.pdf",
            'tipo_archivo'    => 'application/pdf',
            'tamanio'         => 100,
            'curso_id'        => $asignacion->curso_id,
            'grado_id'        => $asignacion->grado_id,
            'seccion_id'      => $asignacion->seccion_id,
            'bimestre'        => 1,
            'anio'            => now()->year,
        ]);

        $this->actingAs($docente)
             ->get(route('docente.archivos.stream', $archivo))
             ->assertNotFound();
    }
}
