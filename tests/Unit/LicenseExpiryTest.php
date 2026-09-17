<?php

namespace Tests\Unit;

use App\Models\License;
use Tests\TestCase;

class LicenseExpiryTest extends TestCase
{
    public function test_license_expires_once_its_full_duration_has_elapsed(): void
    {
        $license = new License(['activated_at' => '2026-01-01 00:00:00', 'duration' => '60']);

        $this->travelTo('2026-01-01 00:00:59');
        $this->assertFalse($license->isExpired());

        $this->travelTo('2026-01-01 00:01:00');
        $this->assertTrue($license->isExpired());
    }

    public function test_unactivated_license_never_expires(): void
    {
        $license = new License(['activated_at' => null, 'duration' => '1']);

        $this->travelTo('2100-01-01');

        $this->assertFalse($license->isExpired());
    }
}
