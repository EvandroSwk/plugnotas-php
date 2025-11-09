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
}