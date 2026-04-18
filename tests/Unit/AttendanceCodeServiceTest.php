<?php

namespace Tests\Unit;

use App\Models\Kegiatan;
use App\Services\AttendanceCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceCodeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_generated_code_is_always_six_uppercase_alphanumeric_characters(): void
    {
        $service = app(AttendanceCodeService::class);
        $kegiatan = Kegiatan::factory()->create();

        foreach (range(1, 10) as $index) {
            $code = $service->generate($kegiatan->fresh())->kode;

            $this->assertSame(6, strlen($code));
            $this->assertMatchesRegularExpression('/^[A-Z0-9]{6}$/', $code);
        }
    }
}
