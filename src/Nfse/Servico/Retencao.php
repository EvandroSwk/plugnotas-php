<?php

namespace EvandroSwk\Plugnotas\Nfse\Servico;

use Respect\Validation\Validator as v;
use EvandroSwk\Plugnotas\Abstracts\BuilderAbstract;
use EvandroSwk\Plugnotas\Common\ValorAliquota;
use EvandroSwk\Plugnotas\Common\PisCofinsValorAliquota;
use EvandroSwk\Plugnotas\Error\ValidationError;

class Retencao extends BuilderAbstract
{
    private $cofins;
    private $csll;
    private $inss;
    private $irrf;
    private $outrasRetencoes;
    private $pis;
    private $cpp;
    private $tipoRetencaoPisCofinsCSLL;

    public function setCofins(PisCofinsValorAliquota $cofins)
    {
        $this->cofins = $cofins;
    }

    public function getCofins()
    {
        return $this->cofins;
    }

    public function setCsll(ValorAliquota $csll)
    {
        $this->csll = $csll;
    }

    public function getCsll()
    {
        return $this->csll;
    }

    public function setInss(ValorAliquota $inss)
    {
        $this->inss = $inss;
    }

    public function getInss()
    {
        return $this->inss;
    }

    public function setIrrf(ValorAliquota $irrf)
    {
        $this->irrf = $irrf;
    }

    public function getIrrf()
    {
        return $this->irrf;
    }

    public function setOutrasRetencoes($outrasRetencoes)
    {
        $this->outrasRetencoes = $outrasRetencoes;
    }

    public function getOutrasRetencoes()
    {
        return $this->outrasRetencoes;
    }

    public function setPis(PisCofinsValorAliquota $pis)
    {
        $this->pis = $pis;
    }

    public function getPis()
    {
        return $this->pis;
    }
    public function setCpp(ValorAliquota $cpp)
    {
        $this->cpp = $cpp;
    }

    public function getCpp()
    {
        return $this->cpp;
    }

    /**
     * Tipo de retenção de PIS/COFINS/CSLL — específico para NFSe Nacional (NT007).
     * Substitui os flags pis.retido e cofins.retido para o padrão nacional.
     * Valores aceitos: "0" a "9" conforme tabela da NT007.
     */
    public function setTipoRetencaoPisCofinsCSLL($tipoRetencaoPisCofinsCSLL)
    {
        if (!v::in(['0','1','2','3','4','5','6','7','8','9'])->validate((string) $tipoRetencaoPisCofinsCSLL)) {
            throw new ValidationError(
                'tipoRetencaoPisCofinsCSLL inválido. Valores aceitos: 0 a 9.'
            );
        }
        $this->tipoRetencaoPisCofinsCSLL = (string) $tipoRetencaoPisCofinsCSLL;
    }

    public function getTipoRetencaoPisCofinsCSLL()
    {
        return $this->tipoRetencaoPisCofinsCSLL;
    }

    public static function fromArray($data)
    {
        $retencao = new Retencao();

        $scalarFields = ['outrasRetencoes', 'tipoRetencaoPisCofinsCSLL'];

        foreach ($data as $key => $value) {
            if (in_array($key, $scalarFields)) {
                $retencao->{'set' . ucfirst($key)}($value);
                continue;
            }

            $retencao->{'set' . ucfirst($key)}(ValorAliquota::fromArray($value));
        }

        return $retencao;
    }
}
