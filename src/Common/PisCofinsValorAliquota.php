<?php

namespace EvandroSwk\Plugnotas\Common;

use Respect\Validation\Validator as v;
use EvandroSwk\Plugnotas\Abstracts\BuilderAbstract;
use EvandroSwk\Plugnotas\Error\ValidationError;

class PisCofinsValorAliquota extends BuilderAbstract
{
    private $aliquota;
    private $valor;
    private $cst;

    public function __construct($valor = 0, $aliquota = 0, $cst = 0)
    {
        $this->setAliquota($aliquota);
        $this->setValor($valor);
        $this->setCst($cst);
    }

    public function setValor($valor)
    {
        if (!v::numericVal()->validate($valor)) {
            throw new ValidationError(
                'Valor deve ser um número.'
            );
        }
        $this->valor = $valor;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setAliquota($aliquota)
    {
        if (!v::numericVal()->validate($aliquota)) {
            throw new ValidationError(
                'Aliquota deve ser um número.'
            );
        }
        $this->aliquota = $aliquota;
    }

    public function getAliquota()
    {
        return $this->aliquota;
    }

    public function setCst($cst)
    {
        if (!v::numericVal()->validate($cst)) {
            throw new ValidationError(
                'Aliquota deve ser um número.'
            );
        }
        $this->cst = $cst;
    }

    public function getCst()
    {
        return $this->cst;
    }
    
}
