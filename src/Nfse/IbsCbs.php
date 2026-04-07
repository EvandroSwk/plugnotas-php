<?php

namespace EvandroSwk\Plugnotas\Nfse;

use Respect\Validation\Validator as v;
use EvandroSwk\Plugnotas\Abstracts\BuilderAbstract;
use EvandroSwk\Plugnotas\Error\ValidationError;

class IbsCbs extends BuilderAbstract
{
    private $finalidadeNFSe;
    private $operacaoPessoal;
    private $codigoOperacao;
    private $tipoOperacao;
    private $tipoEnteGovernamental;
    private $descTipoEnteGovernamental;
    private $pagamentoParceladoAntecipado;
    private $municipioIncidenciaIbsCbs;
    private $referenciasNFSe;

    public function setFinalidadeNFSe($finalidadeNFSe)
    {
        $this->finalidadeNFSe = $finalidadeNFSe;
    }

    public function getFinalidadeNFSe()
    {
        return $this->finalidadeNFSe;
    }

    public function setOperacaoPessoal($operacaoPessoal)
    {
        if (!v::in([0, 1])->validate($operacaoPessoal)) {
            throw new ValidationError(
                'operacaoPessoal deve ser 0 (Não) ou 1 (Sim).'
            );
        }
        $this->operacaoPessoal = $operacaoPessoal;
    }

    public function getOperacaoPessoal()
    {
        return $this->operacaoPessoal;
    }

    public function setCodigoOperacao($codigoOperacao)
    {
        if (!v::stringType()->length(1, 6)->validate($codigoOperacao)) {
            throw new ValidationError(
                'codigoOperacao deve ser uma string de até 6 caracteres.'
            );
        }
        $this->codigoOperacao = $codigoOperacao;
    }

    public function getCodigoOperacao()
    {
        return $this->codigoOperacao;
    }

    public function setTipoOperacao($tipoOperacao)
    {
        $this->tipoOperacao = $tipoOperacao;
    }

    public function getTipoOperacao()
    {
        return $this->tipoOperacao;
    }

    public function setTipoEnteGovernamental($tipoEnteGovernamental)
    {
        $this->tipoEnteGovernamental = $tipoEnteGovernamental;
    }

    public function getTipoEnteGovernamental()
    {
        return $this->tipoEnteGovernamental;
    }

    public function setDescTipoEnteGovernamental($descTipoEnteGovernamental)
    {
        $this->descTipoEnteGovernamental = $descTipoEnteGovernamental;
    }

    public function getDescTipoEnteGovernamental()
    {
        return $this->descTipoEnteGovernamental;
    }

    public function setPagamentoParceladoAntecipado($pagamentoParceladoAntecipado)
    {
        if (!v::boolVal()->validate($pagamentoParceladoAntecipado)) {
            throw new ValidationError(
                'pagamentoParceladoAntecipado deve ser um valor booleano.'
            );
        }
        $this->pagamentoParceladoAntecipado = $pagamentoParceladoAntecipado;
    }

    public function getPagamentoParceladoAntecipado()
    {
        return $this->pagamentoParceladoAntecipado;
    }

    public function setMunicipioIncidenciaIbsCbs($municipioIncidenciaIbsCbs)
    {
        $this->municipioIncidenciaIbsCbs = $municipioIncidenciaIbsCbs;
    }

    public function getMunicipioIncidenciaIbsCbs()
    {
        return $this->municipioIncidenciaIbsCbs;
    }

    public function setReferenciasNFSe(array $referenciasNFSe)
    {
        $this->referenciasNFSe = $referenciasNFSe;
    }

    public function getReferenciasNFSe()
    {
        return $this->referenciasNFSe;
    }
}
