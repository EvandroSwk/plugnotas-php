<?php

namespace EvandroSwk\Plugnotas\Nfse;

use EvandroSwk\Plugnotas\Abstracts\BuilderAbstract;
use EvandroSwk\Plugnotas\Error\ValidationError;

class Impressao extends BuilderAbstract
{
    private $camposCustomizados;

    public function setCamposCustomizados($camposCustomizados)
    {
        if (!is_array($camposCustomizados)) {
            throw new ValidationError(
                'camposCustomizados deve ser um array.'
            );
        }

        $this->camposCustomizados = $camposCustomizados;
    }

    public function getCamposCustomizados()
    {
        return $this->camposCustomizados;
    }
}
