<?php

namespace EvandroSwk\Plugnotas\Tests\Nfse;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use EvandroSwk\Plugnotas\Common\Endereco;
use EvandroSwk\Plugnotas\Common\Telefone;
use EvandroSwk\Plugnotas\Common\Nfse;
use EvandroSwk\Plugnotas\Communication\CallApi;
use EvandroSwk\Plugnotas\Configuration;
use EvandroSwk\Plugnotas\Error\InvalidTypeError;
use EvandroSwk\Plugnotas\Error\RequiredError;
use EvandroSwk\Plugnotas\Error\ValidationError;
use EvandroSwk\Plugnotas\Nfse\Prestador;

class PrestadorTest extends TestCase
{
    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setCertificado
     */
    public function testWithInvlidaCertificateId()
    {
        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('ID de certificado Inválido.');
        $prestador = new Prestador();
        $prestador->setCertificado('123ASD');
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setCpfCnpj
     */
    public function testWithInvalidLengthCpfCnpj()
    {
        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('Campo cpfCnpj deve ter 11 ou 14 números.');
        $prestador = new Prestador();
        $prestador->setCpfCnpj('12345678901234567890');
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setCpfCnpj
     */
    public function testWithInvalidCpfFormation()
    {
        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('CPF inválido.');
        $prestador = new Prestador();
        $prestador->setCpfCnpj('123.456.789-01');
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setCpfCnpj
     */
    public function testWithInvalidCnpjFormation()
    {
        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('CNPJ inválido.');
        $prestador = new Prestador();
        $prestador->setCpfCnpj('12.345.678/0001-90');
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setSimplesNacional
     */
    public function testWithNullSimplesNacional()
    {
        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('Optante do Simples nacional é requerida para NFSe.');
        $prestador = new Prestador();
        $prestador->setSimplesNacional(null);
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setRazaoSocial
     */
    public function testWithNullRazaoSocial()
    {
        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('Razão social é requerida para NFSe.');
        $prestador = new Prestador();
        $prestador->setRazaoSocial(null);
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setEmail
     */
    public function testWithInvalidEmail()
    {
        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('Endereço de email inválido.');
        $prestador = new Prestador();
        $prestador->setEmail('teste');
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getCertificado
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getCpfCnpj
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getEmail
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getEndereco
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getIncentivadorCultural
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getIncentivoFiscal
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getInscricaoMunicipal
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getNomeFantasia
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getRazaoSocial
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getRegimeTributario
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getRegimeTributarioEspecial
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getSimplesNacional
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getTelefone
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::getNfse
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setCertificado
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setCpfCnpj
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setEmail
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setEndereco
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setIncentivadorCultural
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setIncentivoFiscal
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setInscricaoMunicipal
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setNomeFantasia
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setRazaoSocial
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setRegimeTributario
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setRegimeTributarioEspecial
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setSimplesNacional
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setTelefone
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::setNfse
     */
    public function testValidPrestadorCreation()
    {
        $endereco = new Endereco();
        $endereco->setTipoLogradouro('Avenida');
        $endereco->setLogradouro('Duque de Caxias');
        $endereco->setNumero('882');
        $endereco->setComplemento('17 andar');
        $endereco->setTipoBairro('Zona');
        $endereco->setBairro('Zona 7');
        $endereco->setCodigoPais(1058);
        $endereco->getDescricaoPais('Brasil');
        $endereco->setCodigoCidade('4115200');
        $endereco->setDescricaoCidade('Maringá');
        $endereco->setEstado('PR');
        $endereco->setCep('87.020-025');

        $telefone = new Telefone('44', '1234-1234');

        $nfse = new Nfse();
        $nfse->setAtivo(true);
        $nfse->setTipoContrato(1);

        $prestador = new Prestador();
        $prestador->setCertificado('5b855b0926ddb251e0f0ef42');
        $prestador->setCpfCnpj('00.000.000/0001-91');
        $prestador->setEmail('teste@plugnotas.com.br');
        $prestador->setEndereco($endereco);
        $prestador->setIncentivadorCultural(false);
        $prestador->setIncentivoFiscal(false);
        $prestador->setInscricaoMunicipal('8214100099');
        $prestador->setInscricaoEstadual('21548818154845');
        $prestador->setNomeFantasia('Empresa Teste');
        $prestador->setRazaoSocial('Empresa Teste LTDA');
        $prestador->setRegimeTributario(0);
        $prestador->setRegimeTributarioEspecial(0);
        $prestador->setSimplesNacional(0);
        $prestador->setTelefone($telefone);
    
        $this->assertSame($prestador->getCertificado(), '5b855b0926ddb251e0f0ef42');
        $this->assertSame($prestador->getCpfCnpj(), '00000000000191');
        $this->assertSame($prestador->getEmail(), 'teste@plugnotas.com.br');
        $this->assertSame($prestador->getEndereco()->getCep(), '87020025');
        $this->assertSame($prestador->getIncentivadorCultural(), false);
        $this->assertSame($prestador->getIncentivoFiscal(), false);
        $this->assertSame($prestador->getInscricaoMunicipal(), '8214100099');
        $this->assertSame($prestador->getInscricaoEstadual(), '21548818154845');
        $this->assertSame($prestador->getNomeFantasia(), 'Empresa Teste');
        $this->assertSame($prestador->getRazaoSocial(), 'Empresa Teste LTDA');
        $this->assertSame($prestador->getRegimeTributario(), 0);
        $this->assertSame($prestador->getRegimeTributarioEspecial(), 0);
        $this->assertSame($prestador->getSimplesNacional(), 0);
        $this->assertSame($prestador->getTelefone()->getDdd(), '44');
        $this->assertSame($prestador->getTelefone()->getNumero(), '12341234');             
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::fromArray
     */
    public function testBuildFromArray()
    {
        $data = ['certificado' => '5b855b0926ddb251e0f0ef42'];
        $prestador = Prestador::fromArray($data);
        $this->assertInstanceOf(Prestador::class, $prestador);
        $this->assertSame($prestador->getCertificado(), '5b855b0926ddb251e0f0ef42');
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::fromArray
     */
    public function testBuildFromArrayWithNfse()
    {
        $data = [
            'certificado' => '5b855b0926ddb251e0f0ef42',
            'nfse' => [
                'ativo' => true,
                'tipoContrato' => 1
            ]
        ];
        $prestador = Prestador::fromArray($data);
        $this->assertInstanceOf(Prestador::class, $prestador);
        $this->assertSame($prestador->getCertificado(), '5b855b0926ddb251e0f0ef42');
        $this->assertInstanceOf(Nfse::class, $prestador->getNfse());
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::fromArray
     */
    public function testBuildFromArrayWithNfseInvalid()
    {
        $this->expectExceptionMessage(
            'Valor inválido para o TipoContrato. Valores aceitos: null, 0, 1'
        );

        $data = [
            'certificado' => '5b855b0926ddb251e0f0ef42',
            'nfse' => [
                'ativo' => true,
                'tipoContrato' => 3
            ]
        ];
        $prestador = Prestador::fromArray($data);
        $prestador->validate();
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::fromArray
     */
    public function testBuildFromArrayWithEnderecoAndTelefone()
    {
        $data = [
            'certificado' => '5b855b0926ddb251e0f0ef42',
            'telefone' => [
                'ddd' => '44',
                'numero' => '12341234'
            ],
            'endereco' => [
                'logradouro' => 'Rua de teste'
            ]
        ];
        $prestador = Prestador::fromArray($data);
        $this->assertInstanceOf(Prestador::class, $prestador);
        $this->assertSame($prestador->getCertificado(), '5b855b0926ddb251e0f0ef42');
        $this->assertInstanceOf(Endereco::class, $prestador->getEndereco());
        $this->assertInstanceOf(Telefone::class, $prestador->getTelefone());
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::fromArray
     */
    public function testBuildFromArrayWithInvalidParameter()
    {
        $this->expectException(InvalidTypeError::class);
        $this->expectExceptionMessage('Deve ser informado um array.');
        $prestador = Prestador::fromArray('teste');
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::validate
     */
    public function testValidateWithInvalidObject()
    {
        $this->expectException(RequiredError::class);
        $this->expectExceptionMessage(
            'Os parâmetros mínimos para criar um Prestador não foram preenchidos.'
        );
        $data = [
            'certificado' => '5b855b0926ddb251e0f0ef42'
        ];
        $prestador = Prestador::fromArray($data);
        $prestador->validate();
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Prestador::validate
     */
    public function testValidateWithValidObject()
    {
        $data = [
            'cpfCnpj' => '00.000.000/0001-91',
            'razaoSocial' => 'Razao Social do Prestador',
            'simplesNacional' => false,
            'endereco' => [
                'logradouro' => 'Rua de Teste',
                'numero' => '1234',
                'codigoCidade' => '4115200',
                'cep' => '87.050-800'
            ]
        ];
        $prestador = Prestador::fromArray($data);
        $this->assertTrue($prestador->validate());
    }

}

