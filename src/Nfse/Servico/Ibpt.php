<?php

namespace EvandroSwk\Plugnotas\Nfse\Servico;

use Respect\Validation\Validator as v;
use EvandroSwk\Plugnotas\Abstracts\BuilderAbstract;
use EvandroSwk\Plugnotas\Error\ValidationError;


class Ibpt extends BuilderAbstract
{
    private $simplificado;
    private $detalhado;

   

    public function setSimplificado(array $simplificado)
    {
        $this->simplificado = $simplificado;
    }

    public function getSimplificado()
    {
        return $this->simplificado;
    }


    public function setDetalhado(array $detalhado)
    {
  
        $this->detalhado = $detalhado;
    }

    public function getDetalhado()
    {
        return $this->detalhado;
    }

}
