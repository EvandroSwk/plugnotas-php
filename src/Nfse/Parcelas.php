<?php

namespace EvandroSwk\Plugnotas\Nfse;

use EvandroSwk\Plugnotas\Abstracts\BuilderAbstract;
use EvandroSwk\Plugnotas\Error\ValidationError;

class Parcelas extends BuilderAbstract
{
    private $tipoPagamento;
    private $numero;
    private $dataVencimento;
    private $valor;

    public function setTipoPagamento($tipoPagamento)
    {
    
        $this->tipoPagamento = $tipoPagamento;
    }

    public function getTipoPagamento()
    {
        return $this->tipoPagamento;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setDataVencimento($dataVencimento)
    {
        $this->dataVencimento = $dataVencimento;
    }
   
    public function getDataVencimento(){
        return $this->dataVencimento;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getValor()
    {
        return $this->valor;
    }

    
}
