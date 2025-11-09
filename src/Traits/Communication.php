<?php

namespace EvandroSwk\Plugnotas\Traits;

use EvandroSwk\Plugnotas\Communication\CallApi;
use EvandroSwk\Plugnotas\Error\ConfigurationRequiredError;

trait Communication
{
    protected function getCallApiInstance($configuration)
    {
        if (!$configuration) {
            throw new ConfigurationRequiredError('É necessário setar a configuração utilizando o método setConfiguration.');
        }

        return new CallApi($configuration);
    }
}

