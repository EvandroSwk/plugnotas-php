<?php

namespace EvandroSwk\Plugnotas\Nfse\Servico;

use Respect\Validation\Validator as v;
use EvandroSwk\Plugnotas\Abstracts\BuilderAbstract;
use EvandroSwk\Plugnotas\Error\ValidationError;

class Obra extends BuilderAbstract
{
    private $art;
    private $codigo;
    private $cei;

    public function setArt($art)
    {
        $this->art = $art;
    }

    public function getArt()
    {
        return $this->art;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setCei($cei)
    {
        $this->cei = $cei;
    }

    public function getCei()
    {
        return $this->cei;
    }
}
