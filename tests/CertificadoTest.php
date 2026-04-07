<?php

namespace EvandroSwk\Plugnotas\Tests;

use PHPUnit\Framework\TestCase;
use EvandroSwk\Plugnotas\Configuration;
use EvandroSwk\Plugnotas\Certificado;

class CertificadoTest extends TestCase
{
    public function testGetAllCertificates()
    {
        $configuration = new Configuration();

        $certificado = new Certificado();
        $certificado->setConfiguration($configuration);

        $response = $certificado->get();

        $this->assertTrue(property_exists($response, 'body'));
        $this->assertEquals(200, $response->statusCode);
        $this->assertCount(1, $response->body);
    }

    public function testCreateCertificate()
    {
        $configuration = new Configuration();

        $certificado = new Certificado();
        $certificado->setConfiguration($configuration);
        $certificado->setFile(__DIR__.'/../examples/certificado.pfx', 'arquivo.pfx');
        $certificado->setPassword('1234');

        $response = $certificado->create();

        $this->assertEquals(201, $response->statusCode);
        $this->assertTrue(property_exists($response, 'body'));
        $this->assertTrue(property_exists($response->body, 'message'));
        $this->assertTrue(property_exists($response->body->data, 'id'));
    }

    public function testUpdateCertificate()
    {
        $id = md5(uniqid(rand(), true));

        $configuration = new Configuration();

        $certificado = new Certificado();
        $certificado->setConfiguration($configuration);
        $certificado->setFile(__DIR__.'/../examples/certificado-update.pfx', 'arquivo-update.pfx');
        $certificado->setPassword('1234');

        $response = $certificado->update($id);

        $this->assertEquals(200, $response->statusCode);
        $this->assertTrue(property_exists($response, 'body'));
        $this->assertTrue(property_exists($response->body, 'message'));
    }
}
