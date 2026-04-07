<?php

namespace EvandroSwk\Plugnotas\Tests\Nfse;

use PHPUnit\Framework\TestCase;
use EvandroSwk\Plugnotas\Nfse\IbsCbs;
use EvandroSwk\Plugnotas\Error\ValidationError;

class IbsCbsTest extends TestCase
{
    public function testWithValidData()
    {
        $ibsCbs = new IbsCbs();
        $ibsCbs->setCodigoOperacao('AB1234');
        $ibsCbs->setOperacaoPessoal(0);
        $ibsCbs->setPagamentoParceladoAntecipado(false);
        $ibsCbs->setMunicipioIncidenciaIbsCbs('4115200');

        $this->assertSame('AB1234', $ibsCbs->getCodigoOperacao());
        $this->assertSame(0, $ibsCbs->getOperacaoPessoal());
        $this->assertFalse($ibsCbs->getPagamentoParceladoAntecipado());
        $this->assertSame('4115200', $ibsCbs->getMunicipioIncidenciaIbsCbs());
    }

    public function testCodigoOperacaoInvalid()
    {
        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('codigoOperacao deve ser uma string de até 6 caracteres.');

        $ibsCbs = new IbsCbs();
        $ibsCbs->setCodigoOperacao('TOOLONG');
    }

    public function testOperacaoPessoalInvalid()
    {
        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('operacaoPessoal deve ser 0 (Não) ou 1 (Sim).');

        $ibsCbs = new IbsCbs();
        $ibsCbs->setOperacaoPessoal(2);
    }

    public function testPagamentoParceladoAntecipadoInvalid()
    {
        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('pagamentoParceladoAntecipado deve ser um valor booleano.');

        $ibsCbs = new IbsCbs();
        $ibsCbs->setPagamentoParceladoAntecipado('sim');
    }

    public function testReferenciasNFSe()
    {
        $ibsCbs = new IbsCbs();
        $referencias = ['ref1', 'ref2'];
        $ibsCbs->setReferenciasNFSe($referencias);

        $this->assertSame($referencias, $ibsCbs->getReferenciasNFSe());
    }

    public function testToArray()
    {
        $ibsCbs = new IbsCbs();
        $ibsCbs->setCodigoOperacao('OP001');
        $ibsCbs->setOperacaoPessoal(1);
        $ibsCbs->setFinalidadeNFSe(1);
        $ibsCbs->setTipoOperacao(2);

        $array = $ibsCbs->toArray();

        $this->assertArrayHasKey('codigoOperacao', $array);
        $this->assertArrayHasKey('operacaoPessoal', $array);
        $this->assertArrayHasKey('finalidadeNFSe', $array);
        $this->assertArrayHasKey('tipoOperacao', $array);
        $this->assertSame('OP001', $array['codigoOperacao']);
    }

    public function testFromArray()
    {
        $data = [
            'codigoOperacao' => 'OP002',
            'operacaoPessoal' => 0,
            'finalidadeNFSe' => 2,
            'pagamentoParceladoAntecipado' => true,
        ];

        $ibsCbs = IbsCbs::fromArray($data);

        $this->assertInstanceOf(IbsCbs::class, $ibsCbs);
        $this->assertSame('OP002', $ibsCbs->getCodigoOperacao());
        $this->assertSame(0, $ibsCbs->getOperacaoPessoal());
    }
}
