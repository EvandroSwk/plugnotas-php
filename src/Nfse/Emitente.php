<?php

namespace EvandroSwk\Plugnotas\Nfse;

use Respect\Validation\Validator as v;
use EvandroSwk\Plugnotas\Abstracts\BuilderAbstract;
use EvandroSwk\Plugnotas\Error\ValidationError;

class Emitente extends BuilderAbstract
{
    private $tipo;
    private $codigoCidade;

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setCodigoCidade($codigoCidade)
    {
        $clean = preg_replace('/[^0-9]/', '', (string) $codigoCidade);
        if (strlen($clean) !== 7) {
            throw new ValidationError(
                'codigoCidade deve conter exatamente 7 dígitos (código IBGE).'
            );
        }
        $this->codigoCidade = $clean;
    }

    public function getCodigoCidade()
    {
        return $this->codigoCidade;
    }
}
