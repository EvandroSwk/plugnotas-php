<?php

require '../vendor/autoload.php';

use EvandroSwk\Plugnotas\Configuration;
use EvandroSwk\Plugnotas\Certificado;
use EvandroSwk\Plugnotas\Error\RequiredError;
use EvandroSwk\Plugnotas\Error\ValidationError;

try {
    // Criando configuração (este objeto é onde você irá colocar seu api-key)
    $configuration = new Configuration();

    // Criando um Certificado
    $certificado = new Certificado();
    $certificado->setConfiguration($configuration);
    $certificado->setFile(__DIR__.'/certificado.pfx', 'arquivo.pfx');
    $certificado->setPassword('1234');

    $response = $certificado->create(); // A resposta sempre será um objeto EvandroSwk\Plugnotas\Communication\Response
    var_dump($response);
} catch (ValidationError $e) {
    // Algum campo foi informado no formato errado
    var_dump($e);
} catch (RequiredError $e) {
    // Campos requeridos não foram informados
    var_dump($e);
} catch (\Exception $e) {
    // Algum erro não esperado
    var_dump($e);
}
