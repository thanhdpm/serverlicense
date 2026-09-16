<?php

namespace Tests\Unit;

use Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_duration_format(): void
    {
        $this->assertEquals('30 giây', durationFormat(30));
        $this->assertEquals('2 phút', durationFormat(120));
        $this->assertEquals('1 giờ', durationFormat(3600));
        $this->assertEquals('1 ngày', durationFormat(86400));
        $this->assertEquals('1 tuần', durationFormat(604800));
        $this->assertEquals('1 tháng', durationFormat(2630000));
        $this->assertEquals('1 năm', durationFormat(31557600));
    }

    public function test_period_detection(): void
    {
        $this->assertEquals('seconds', periodDetection(30));
        $this->assertEquals('minutes', periodDetection(120));
        $this->assertEquals('hours', periodDetection(3600));
        $this->assertEquals('days', periodDetection(86400));
        $this->assertEquals('weeks', periodDetection(604800));
        $this->assertEquals('months', periodDetection(2630000));
        $this->assertEquals('years', periodDetection(31557600));
    }
}
