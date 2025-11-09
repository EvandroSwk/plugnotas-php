<?php

namespace EvandroSwk\Plugnotas\Builders;

use EvandroSwk\Plugnotas\Nfse;
use EvandroSwk\Plugnotas\Nfse\CidadePrestacao;
use EvandroSwk\Plugnotas\Nfse\Impressao;
use EvandroSwk\Plugnotas\Nfse\Prestador;
use EvandroSwk\Plugnotas\Nfse\Rps;
use EvandroSwk\Plugnotas\Nfse\Servico;
use EvandroSwk\Plugnotas\Nfse\Tomador;
use EvandroSwk\Plugnotas\Error\InvalidTypeError;

class NfseBuilder
{
    private $cidadePrestacao;
    private $rps;
    private $tomador;
    private $prestador;
    private $servico;
    private $impressao;

    private function buildArrayServices($services, $class)
    {
        $arrayServices = [];
        foreach($services as $service) {
            $instanceService = $class::fromArray($service);
            array_push($arrayServices, $instanceService->toArray());
        }

        return $arrayServices;
    }

    private function callFromArray($name, $class, $data)
    {
        if (is_array($data)) {
            if($name === 'servico') {
                $this->servico = $this->buildArrayServices($data, $class);
            } else {
                $this->{$name} = $class::fromArray($data);
            }

            return $this;
        }

        if (is_object($data) && get_class($data) === $class) {
            $this->{$name} = $data;
            return $this;
        }

        throw new InvalidTypeError(
            'Deve ser informado um array ou um objeto do tipo: ' . $class
        );
    }

    public function withTomador($data)
    {
        return $this->callFromArray('tomador', Tomador::class, $data);
    }

    public function withPrestador($data)
    {
        return $this->callFromArray('prestador', Prestador::class, $data);
    }

    public function withServicos(array $data)
    {
        return $this->callFromArray('servico', Servico::class, $data);
    }

    public function withRps($data)
    {
        return $this->callFromArray('rps', Rps::class, $data);
    }

    public function withImpressao($data)
    {
        return $this->callFromArray('impressao', Impressao::class, $data);
    }

    public function withCidadePrestacao($data)
    {
        return $this->callFromArray('cidadePrestacao', CidadePrestacao::class, $data);
    }

    public function build($data = [])
    {
        $nfse = Nfse::fromArray($data);

        $properties = [
            'cidadePrestacao',
            'impressao',
            'prestador',
            'rps',
            'servico',
            'tomador'
        ];

        foreach ($properties as $p) {
            if (!is_null($this->{$p})) {
                $nfse->{'set' . ucfirst($p)}($this->{$p});
            }
        }

        return $nfse;
    }
}
