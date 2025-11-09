<?php

namespace EvandroSwk\Plugnotas\Tests\Nfse;

use PHPUnit\Framework\TestCase;
use EvandroSwk\Plugnotas\Nfse\Rps;
use EvandroSwk\Plugnotas\Error\ValidationError;

class RpsTest extends TestCase
{
    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Rps::setCompetencia
     */
    public function testWithInvalidCompetencia()
    {
        $this->expectException(\TypeError::class);
        $rps = new Rps();
        $rps->setCompetencia('teste');
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Rps::setDataEmissao
     */
    public function testWithInvalidDataEmissao()
    {
        $this->expectException(\TypeError::class);
        $rps = new Rps();
        $rps->setDataEmissao('teste');
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Rps::setDataEmissao
     * @covers EvandroSwk\Plugnotas\Nfse\Rps::setCompetencia
     * @covers EvandroSwk\Plugnotas\Nfse\Rps::getDataEmissao
     * @covers EvandroSwk\Plugnotas\Nfse\Rps::getCompetencia
     */
    public function testWithValidRpsData()
    {
        $dateBase = new \DateTime('now');
        $competencia = $dateBase->format('Y-m-d');
        $dataEmissao = $dateBase->format('Y-m-d\TH:i:s');
        $rps = new Rps();
        $rps->setDataEmissao($dateBase);
        $rps->setCompetencia($dateBase);

        $this->assertSame($rps->getDataEmissao(), $dataEmissao);
        $this->assertSame($rps->getCompetencia(), $competencia);
    }
}
