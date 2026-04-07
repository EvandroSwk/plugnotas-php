<?php

namespace EvandroSwk\Plugnotas\Tests\Nfse;

use PHPUnit\Framework\TestCase;
use EvandroSwk\Plugnotas\Nfse\Emitente;
use EvandroSwk\Plugnotas\Error\ValidationError;

class EmitenteTest extends TestCase
{
    public function testWithValidData()
    {
        $emitente = new Emitente();
        $emitente->setTipo(1);
        $emitente->setCodigoCidade('4115200');

        $this->assertSame(1, $emitente->getTipo());
        $this->assertSame('4115200', $emitente->getCodigoCidade());
    }

    public function testCodigoCidadeStripsFormatting()
    {
        $emitente = new Emitente();
        $emitente->setCodigoCidade('411.5200');

        $this->assertSame('4115200', $emitente->getCodigoCidade());
    }

    public function testCodigoCidadeInvalidLength()
    {
        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('codigoCidade deve conter exatamente 7 dígitos (código IBGE).');

        $emitente = new Emitente();
        $emitente->setCodigoCidade('123456');
    }

    public function testCodigoCidadeInvalidLengthTooLong()
    {
        $this->expectException(ValidationError::class);

        $emitente = new Emitente();
        $emitente->setCodigoCidade('12345678');
    }

    public function testToArray()
    {
        $emitente = new Emitente();
        $emitente->setTipo(1);
        $emitente->setCodigoCidade('4115200');

        $array = $emitente->toArray();

        $this->assertArrayHasKey('tipo', $array);
        $this->assertArrayHasKey('codigoCidade', $array);
        $this->assertSame(1, $array['tipo']);
        $this->assertSame('4115200', $array['codigoCidade']);
    }

    public function testFromArray()
    {
        $data = [
            'tipo' => 1,
            'codigoCidade' => '4115200',
        ];

        $emitente = Emitente::fromArray($data);

        $this->assertInstanceOf(Emitente::class, $emitente);
        $this->assertSame(1, $emitente->getTipo());
        $this->assertSame('4115200', $emitente->getCodigoCidade());
    }
}
