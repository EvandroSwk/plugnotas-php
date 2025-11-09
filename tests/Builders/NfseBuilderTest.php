<?php
namespace EvandroSwk\Plugnotas\Tests\Builders;

use PHPUnit\Framework\TestCase;
use EvandroSwk\Plugnotas\Builders\NfseBuilder;
use EvandroSwk\Plugnotas\Common\Endereco;
use EvandroSwk\Plugnotas\Common\Telefone;
use EvandroSwk\Plugnotas\Common\Nfse;
use EvandroSwk\Plugnotas\Error\InvalidTypeError;
use EvandroSwk\Plugnotas\Error\ValidationError;
use EvandroSwk\Plugnotas\Nfse\CidadePrestacao;
use EvandroSwk\Plugnotas\Nfse\Impressao;
use EvandroSwk\Plugnotas\Nfse\Prestador;
use EvandroSwk\Plugnotas\Nfse\Rps;
use EvandroSwk\Plugnotas\Nfse\Servico;
use EvandroSwk\Plugnotas\Nfse\Tomador;

class NfseBuilderTest extends TestCase
{
    /**
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::withCidadePrestacao
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::build
     */
    public function testWithWrongObjectType()
    {
        $this->expectException(InvalidTypeError::class);
        $this->expectExceptionMessage(
            'Deve ser informado um array ou um objeto do tipo: EvandroSwk\Plugnotas\Nfse\CidadePrestacao'
        );
        $nfse = (new NfseBuilder)
            ->withCidadePrestacao('teste')
            ->build();
    }

    /**
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::withCidadePrestacao
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::build
     */
    public function testWithOneObject()
    {
        $nfse = (new NfseBuilder)
            ->withCidadePrestacao(CidadePrestacao::fromArray([
                'codigo' => '123'
            ]))
            ->build();
        $this->assertInstanceOf(CidadePrestacao::class, $nfse->getCidadePrestacao());
    }

    /**
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::build
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::callFromArray
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::withRps
     */
    public function testWithRpsObject()
    {
        $dateCompare = new \DateTime('now');
        $rps = new Rps();
        $rps->setDataEmissao($dateCompare);
        $rps->setCompetencia($dateCompare);

        $nfse = (new NfseBuilder)
            ->withRps($rps)
            ->build();

        $this->assertInstanceOf(Rps::class, $nfse->getRps());
    }

    /**
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::build
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::callFromArray
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::withRps
     */
    public function testWithInvalidTypeObject()
    {
        $this->expectException(InvalidTypeError::class);
        $this->expectExceptionMessage(
            'Deve ser informado um array ou um objeto do tipo: ' . Prestador::class
        );

        $dateCompare = new \DateTime('now');
        $rps = new Rps();
        $rps->setDataEmissao($dateCompare);
        $rps->setCompetencia($dateCompare);

        $nfse = (new NfseBuilder)
            ->withPrestador($rps)
            ->build();
    }

    /**
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::build
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::callFromArray
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::withCidadePrestacao
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::withImpressao
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::withPrestador
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::withRps
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::withServicos
     * @covers EvandroSwk\Plugnotas\Builders\NfseBuilder::withTomador
     */
    public function testWithValidData()
    {
        $services = [];
        array_push($services, [
            'codigo' => 'codigo',
            'discriminacao' => 'discriminação',
            'codigoTributacao' => null,
            'cnae' => 'cnae',
            'iss' => [
                'aliquota' => 1.01
            ],
            'valor' => [
                'servico' => 10
            ]
        ]);

        array_push($services, [
            'codigo' => 'codigo2',
            'discriminacao' => 'discriminação2',
            'codigoTributacao' => null,
            'cnae' => 'cnae2',
            'iss' => [
                'aliquota' => 1.01
            ],
            'valor' => [
                'servico' => 10
            ]
        ]);

        $nfse = (new NfseBuilder)
            ->withCidadePrestacao([
                'codigo' => '123'
            ])
            ->withTomador([
                'cpfCnpj' => '00.000.000/0001-91',
                'razaoSocial' => 'Tomador Teste'
            ])
            ->withPrestador([
                'cpfCnpj' => '00.000.000/0001-91',
                'razaoSocial' => 'Prestador Teste'
            ])
            ->withServicos($services)
            ->withRps([
                'dataEmissao' => new \DateTime('2019-02-27')
            ])
            ->withImpressao([
                'camposCustomizados' => [
                    'teste' => 'teste impressao'
                ]
            ])
            ->build([
                'enviarEmail' => true,
                'idIntegracao' => 'asdf1234',
                'substituicao' => false
            ]);

        $this->assertInstanceOf(CidadePrestacao::class, $nfse->getCidadePrestacao());
        $this->assertInstanceOf(Prestador::class, $nfse->getPrestador());
        $this->assertInstanceOf(Rps::class, $nfse->getRps());
        $this->assertEquals([
            [
                'codigo' => 'codigo',
                'discriminacao' => 'discriminação',
                'cnae' => 'cnae',
                'iss' => [
                    'aliquota' => 1.01,
                    "exigibilidade" => null,
                    "processoSuspensao" => null,
                    "retido" => null,
                    "tipoTributacao" => null,
                    "valor" => null,
                    "valorRetido" => null,
                ],
                'valor' => [
                    'servico' => 10,
                    "baseCalculo" => null,
                    "deducoes" => null,
                    "descontoCondicionado" => null,
                    "descontoIncondicionado" => null,
                    "liquido" =>null,
                    'unitario' => null,
                    'valorAproximadoTributos' => null
                ]
            ],
            [
                'codigo' => 'codigo2',
                'discriminacao' => 'discriminação2',
                'cnae' => 'cnae2',
                'iss' => [
                    'aliquota' => 1.01,
                    "exigibilidade" => null,
                    "processoSuspensao" => null,
                    "retido" => null,
                    "tipoTributacao" => null,
                    "valor" => null,
                    "valorRetido" => null,
                ],
                'valor' => [
                    'servico' => 10,
                    "baseCalculo" => null,
                    "deducoes" => null,
                    "descontoCondicionado" => null,
                    "descontoIncondicionado" => null,
                    "liquido" =>null,
                    'unitario' => null,
                    'valorAproximadoTributos' => null

                ]
            ]
        ], $nfse->getServico());
        $this->assertInstanceOf(Tomador::class, $nfse->getTomador());
        $this->assertInstanceOf(Impressao::class, $nfse->getImpressao());
        $this->assertSame(true, $nfse->getEnviarEmail());
        $this->assertSame('asdf1234', $nfse->getIdIntegracao());
        $this->assertSame(false, $nfse->getSubstituicao());
    }
}