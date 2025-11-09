<?php

namespace EvandroSwk\Plugnotas\Tests\Nfse;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use EvandroSwk\Plugnotas\Common\ValorAliquota;
use EvandroSwk\Plugnotas\Common\PisCofinsValorAliquota;
use EvandroSwk\Plugnotas\Communication\CallApi;
use EvandroSwk\Plugnotas\Configuration;
use EvandroSwk\Plugnotas\Error\RequiredError;
use EvandroSwk\Plugnotas\Error\ValidationError;
use EvandroSwk\Plugnotas\Nfse\Servico;
use EvandroSwk\Plugnotas\Nfse\Servico\Deducao;
use EvandroSwk\Plugnotas\Nfse\Servico\Evento;
use EvandroSwk\Plugnotas\Nfse\Servico\Iss;
use EvandroSwk\Plugnotas\Nfse\Servico\Obra;
use EvandroSwk\Plugnotas\Nfse\Servico\Retencao;
use EvandroSwk\Plugnotas\Nfse\Servico\Valor;

class ServicoTest extends TestCase
{
    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setId
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::fromArray
     */
    public function testBuildFromArrayWithInvalidParameter()
    {
        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('Id inválido.');
        $data = [
            'id' => '1234'
        ];
        $servico = Servico::fromArray($data);
    }


    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getCnae
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getCodigo
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getCodigoCidadeIncidencia
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getCodigoTributacao
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getDeducao
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getDescricaoCidadeIncidencia
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getDiscriminacao
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getEvento
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getIdIntegracao
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getInformacoesLegais
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getIss
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getObra
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getRetencao
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::getValor
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setCnae
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setCodigo
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setCodigoCidadeIncidencia
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setCodigoTributacao
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setDeducao
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setDescricaoCidadeIncidencia
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setDiscriminacao
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setEvento
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setIdIntegracao
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setInformacoesLegais
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setIss
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setObra
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setRetencao
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::setValor
     */
    public function testWithValidData()
    {
        $deducao = new Deducao();
        $deducao->setTipo(99);
        $deducao->setDescricao('Teste de deducao');

        $evento = new Evento();
        $evento->setCodigo('4051200');
        $evento->setDescricao('CONFERENCIA');

        $iss = new Iss();
        $iss->setAliquota(0.03);
        $iss->setExigibilidade(1);
        $iss->setProcessoSuspensao('1234');
        $iss->setRetido(true);
        $iss->setTipoTributacao(1);
        $iss->setValor(12.30);
        $iss->setValorRetido(1.23);

        $obra = new Obra();
        $obra->setArt('6270201');
        $obra->setCodigo('21');

        $retencao = new Retencao();
        $retencao->setCofins(new PisCofinsValorAliquota(100.10, 1.01, 0.02));
        $retencao->setCsll(new ValorAliquota(202.20, 2.02));
        $retencao->setInss(new ValorAliquota(303.30, 3.03));
        $retencao->setIrrf(new ValorAliquota(404.40, 4.04));
        $retencao->setOutrasRetencoes(new ValorAliquota(505.50, 5.05));
        $retencao->setPis(new PisCofinsValorAliquota(606.60, 6.06,0.01));

        $valor = new Valor();
        $valor->setBaseCalculo(0.01);
        $valor->setDeducoes(0.02);
        $valor->setDescontoCondicionado(0.03);
        $valor->setDescontoIncondicionado(0.04);
        $valor->setLiquido(0.05);
        $valor->setServico(0.06);

        $servico = new Servico();
        $servico->setCnae('4751201');
        $servico->setCodigo('1.02');
        $servico->setCodigoCidadeIncidencia('4115200');
        $servico->setCodigoTributacao('4115200');
        $servico->setDeducao($deducao);
        $servico->setDescricaoCidadeIncidencia('MARINGA');
        $servico->setDiscriminacao('Programação de software');
        $servico->setEvento($evento);
        $servico->setIdIntegracao('A001XT');
        $servico->setInformacoesLegais('Informações necessárias a serem adicionadas na NFSe');
        $servico->setIss($iss);
        $servico->setObra($obra);
        $servico->setRetencao($retencao);
        $servico->setValor($valor);

        $this->assertSame($servico->getCnae(), '4751201');
        $this->assertSame($servico->getCodigo(), '1.02');
        $this->assertSame($servico->getCodigoCidadeIncidencia(), '4115200');
        $this->assertSame($servico->getCodigoTributacao(), '4115200');
        $this->assertSame($servico->getDeducao()->getTipo(), 99);
        $this->assertSame($servico->getDescricaoCidadeIncidencia(), 'MARINGA');
        $this->assertSame($servico->getDiscriminacao(), 'Programação de software');
        $this->assertSame($servico->getEvento()->getCodigo(), '4051200');
        $this->assertSame($servico->getIdIntegracao(), 'A001XT');
        $this->assertSame($servico->getInformacoesLegais(), 'Informações necessárias a serem adicionadas na NFSe');
        $this->assertSame($servico->getIss()->getAliquota(), 0.03);
        $this->assertSame($servico->getObra()->getArt(), '6270201');
        $this->assertSame($servico->getRetencao()->getPis()->getValor(), 606.60);
        $this->assertSame($servico->getValor()->getBaseCalculo(), 0.01);
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::validate
     */
    public function testValidateWithInvalidObject()
    {
        $this->expectException(RequiredError::class);
        $this->expectExceptionMessage(
            'Os parâmetros mínimos para criar um Serviço não foram preenchidos.'
        );
        $data = [
            'id' => '5b855b0926ddb251e0f0ef42'
        ];
        $servico = Servico::fromArray($data);
        $servico->validate();
    }

    /**
     * @covers EvandroSwk\Plugnotas\Nfse\Servico::validate
     */
    public function testValidateWithValidObject()
    {
        $data = [
            'codigo' => '1.02',
            'discriminacao' => 'Exemplo',
            'cnae' => '4751201',
            'iss' => [
                'aliquota' => '3'
            ],
            'valor' => [
                'servico' => 1500.03
            ]
        ];
        $servico = Servico::fromArray($data);
        $this->assertTrue($servico->validate());
    }

  
}
