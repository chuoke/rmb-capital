<?php

namespace Chuoke\RmbCapital\Tests;

use Chuoke\RmbCapital\RmbCapital;
use PHPUnit\Framework\TestCase;

class RmbCapitalTest extends TestCase
{
    /** @test */
    public function test_normal_convert()
    {
        $rmbCapital = new RmbCapital();

        $cases = [
            '人民币零元整' => 0,
            '人民币柒分' => 0.07,
            '人民币伍角柒分' => 0.57,
            '人民币肆角整' => 0.40,
            '人民币壹元整' => 1,
            '人民币壹元贰角整' => 1.2,
            '人民币壹佰元整' => 100,
            '人民币壹佰零壹元整' => 101,
            '人民币壹佰壹拾元整' => 110,
            '人民币叁佰壹拾元零捌分' => 310.08,
            '人民币贰拾万陆仟元柒角伍分' => 206000.75,
            '人民币陆万柒仟捌佰零玖元零贰分' => 67809.02,
        ];

        foreach ($cases as $capital => $val) {
            $this->assertSame($capital, $rmbCapital->convert($val));
        }
    }

    public function test_result_witout_prefix_convert()
    {
        $this->assertSame(
            '陆万柒仟捌佰零玖元零贰分',
            (new RmbCapital())->withoutPrefix()->convert(67809.02)
        );
    }
}
