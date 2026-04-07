<?php

namespace EvandroSwk\Plugnotas\Tests;


use PHPUnit\Framework\TestCase;
use EvandroSwk\Plugnotas\Builders\NfseBuilder;
use EvandroSwk\Plugnotas\Common\Endereco;
use EvandroSwk\Plugnotas\Common\Telefone;
use EvandroSwk\Plugnotas\Common\ValorAliquota;
use EvandroSwk\Plugnotas\Configuration;
use EvandroSwk\Plugnotas\Error\RequiredError;
use EvandroSwk\Plugnotas\Error\ValidationError;
use EvandroSwk\Plugnotas\Nfse;
use EvandroSwk\Plugnotas\Nfse\CidadePrestacao;
use EvandroSwk\Plugnotas\Nfse\Impressao;
use EvandroSwk\Plugnotas\Nfse\Prestador;
use EvandroSwk\Plugnotas\Nfse\Rps;
use EvandroSwk\Plugnotas\Nfse\Servico;
use EvandroSwk\Plugnotas\Nfse\Servico\Deducao;
use EvandroSwk\Plugnotas\Nfse\Servico\Evento;
use EvandroSwk\Plugnotas\Nfse\Servico\Iss;
use EvandroSwk\Plugnotas\Nfse\Servico\Obra;
use EvandroSwk\Plugnotas\Nfse\Servico\Retencao;
use EvandroSwk\Plugnotas\Nfse\Servico\Valor;
use EvandroSwk\Plugnotas\Nfse\Tomador;
use EvandroSwk\Plugnotas\Nfse\Emitente;
use EvandroSwk\Plugnotas\Nfse\IbsCbs;

class NfseTest extends TestCase
{

    public function testValidateWithValidData()
    {
        $services = [];
        array_push($services, [
            'codigo' => 'codigo',
            'discriminacao' => 'discriminação',
            'cnae' => 'cnae',
            'iss' => [
                'aliquota' => 1.01
            ],
            'valor' => [
                'servico' => 10
            ]
        ]);

        $nfse = (new NfseBuilder)
            ->withPrestador([
                'cpfCnpj' => '00.000.000/0001-91',
                'inscricaoMunicipal' => '123456',
                'razaoSocial' => 'Razao Social do Prestador',
                'endereco' => [
                    'logradouro' => 'Rua de Teste',
                    'numero' => '1234',
                    'codigoCidade' => '4115200',
                    'cep' => '87.050-800'
                ]
            ])
            ->withTomador([
                'cpfCnpj' => '000.000.001-91',
                'razaoSocial' => 'Razao Social do Tomador'
            ])
            ->withServicos($services)
            ->build([]);
        $this->assertTrue($nfse->validate());
    }

    public function testVersaoValid()
    {
        $nfse = new Nfse();
        $nfse->setVersao('1.01');
        $this->assertSame('1.01', $nfse->getVersao());

        $nfse->setVersao('1.00');
        $this->assertSame('1.00', $nfse->getVersao());
    }

    public function testVersaoInvalid()
    {
        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('Versão NFSe Nacional inválida.');

        $nfse = new Nfse();
        $nfse->setVersao('2.00');
    }

    public function testEmitenteSetAndGet()
    {
        $emitente = new Emitente();
        $emitente->setTipo(1);
        $emitente->setCodigoCidade('4115200');

        $nfse = new Nfse();
        $nfse->setEmitente($emitente);

        $this->assertSame($emitente, $nfse->getEmitente());
        $this->assertSame(1, $nfse->getEmitente()->getTipo());
    }

    public function testIbsCbsSetAndGet()
    {
        $ibsCbs = new IbsCbs();
        $ibsCbs->setCodigoOperacao('OP001');
        $ibsCbs->setOperacaoPessoal(0);

        $nfse = new Nfse();
        $nfse->setIbsCbs($ibsCbs);

        $this->assertSame($ibsCbs, $nfse->getIbsCbs());
        $this->assertSame('OP001', $nfse->getIbsCbs()->getCodigoOperacao());
    }

    public function testFromArrayWithNfseNacionalFields()
    {
        $data = [
            'prestador' => ['cpfCnpj' => '00000000000191'],
            'versao' => '1.01',
            'emitente' => ['tipo' => 1, 'codigoCidade' => '4115200'],
            'ibsCbs' => ['codigoOperacao' => 'OP002', 'operacaoPessoal' => 0],
        ];

        $nfse = Nfse::fromArray($data);

        $this->assertInstanceOf(Nfse::class, $nfse);
        $this->assertSame('1.01', $nfse->getVersao());
        $this->assertInstanceOf(Emitente::class, $nfse->getEmitente());
        $this->assertSame('4115200', $nfse->getEmitente()->getCodigoCidade());
        $this->assertInstanceOf(IbsCbs::class, $nfse->getIbsCbs());
        $this->assertSame('OP002', $nfse->getIbsCbs()->getCodigoOperacao());
    }
}