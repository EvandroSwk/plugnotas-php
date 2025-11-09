<?php

namespace EvandroSwk\Plugnotas\Tests\Communication;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use EvandroSwk\Plugnotas\Communication\CallApi;
use EvandroSwk\Plugnotas\Configuration;

class CallApiTest extends TestCase
{
    /**
     * @covers EvandroSwk\Plugnotas\Communication\CallApi::__construct
     * @covers EvandroSwk\Plugnotas\Communication\CallApi::setClient
     * @covers EvandroSwk\Plugnotas\Communication\CallApi::send
     */
    public function testGetSuccessFull()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"teste":"teste"}')
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $configuration = new Configuration(
            Configuration::TYPE_ENVIRONMENT_SANDBOX, // Ambiente a ser enviada a requisição
            '2da392a6-79d2-4304-a8b7-959572c7e44d' // API-Key
        );

        $communication = new CallApi($configuration);
        $communication->setClient($client);

        $response = $communication->send('GET', '/', null);

        $this->assertSame(200, $response->statusCode);
        $this->assertSame('teste', $response->body->teste);
    }

    /**
     * @covers EvandroSwk\Plugnotas\Communication\CallApi::__construct
     * @covers EvandroSwk\Plugnotas\Communication\CallApi::setClient
     * @covers EvandroSwk\Plugnotas\Communication\CallApi::send
     */
    public function testPostSuccessFull()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"teste":"teste"}')
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $configuration = new Configuration(
            Configuration::TYPE_ENVIRONMENT_SANDBOX, // Ambiente a ser enviada a requisição
            '2da392a6-79d2-4304-a8b7-959572c7e44d' // API-Key
        );

        $communication = new CallApi($configuration);
        $communication->setClient($client);

        $response = $communication->send('POST', '/', null);

        $this->assertSame(200, $response->statusCode);
        $this->assertSame('teste', $response->body->teste);
    }

    /**
     * @covers EvandroSwk\Plugnotas\Communication\CallApi::__construct
     * @covers EvandroSwk\Plugnotas\Communication\CallApi::setClient
     * @covers EvandroSwk\Plugnotas\Communication\CallApi::send
     */
    public function testPostException()
    {
        $mock = new MockHandler([
            new ClientException(
                'Bad Request',
                new Request('POST', '{"teste":"teste"}'),
                new Response(400, [], '{"teste":"teste"}')
            )
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $configuration = new Configuration(
            Configuration::TYPE_ENVIRONMENT_SANDBOX, // Ambiente a ser enviada a requisição
            '2da392a6-79d2-4304-a8b7-959572c7e44d' // API-Key
        );

        $communication = new CallApi($configuration);
        $communication->setClient($client);

        $response = $communication->send('POST', '/', null);

        $this->assertSame(400, $response->statusCode);
        $this->assertSame('teste', $response->body->teste);
    }

    /**
     * @covers EvandroSwk\Plugnotas\Communication\CallApi::download
     */
    public function testDownloadGetSuccessFull()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"teste":"teste"}')
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $configuration = new Configuration(
            Configuration::TYPE_ENVIRONMENT_SANDBOX, // Ambiente a ser enviada a requisição
            '2da392a6-79d2-4304-a8b7-959572c7e44d' // API-Key
        );

        $communication = new CallApi($configuration);
        $communication->setClient($client);

        $response = $communication->download('GET', '/', null, 'examples/tmp/test.json');

        $this->assertSame(200, $response->statusCode);
        $this->assertSame(17, filesize('examples/tmp/test.json'));
        unlink('examples/tmp/test.json');
    }

    /**
     * @covers EvandroSwk\Plugnotas\Communication\CallApi::download
     */
    public function testDownloadPostSuccessFull()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"teste":"teste"}')
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $configuration = new Configuration(
            Configuration::TYPE_ENVIRONMENT_SANDBOX, // Ambiente a ser enviada a requisição
            '2da392a6-79d2-4304-a8b7-959572c7e44d' // API-Key
        );

        $communication = new CallApi($configuration);
        $communication->setClient($client);

        $response = $communication->download('POST', '/', null, 'examples/tmp/test.json');

        $this->assertSame(200, $response->statusCode);
        $this->assertSame(17, filesize('examples/tmp/test.json'));
        unlink('examples/tmp/test.json');
    }

    /**
     * @covers EvandroSwk\Plugnotas\Communication\CallApi::download
     */
    public function testDownloadPostException()
    {
        $mock = new MockHandler([
            new ClientException(
                'Bad Request',
                new Request('POST', '{"teste":"teste"}'),
                new Response(400, [], '{"teste":"teste"}')
            )
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $configuration = new Configuration(
            Configuration::TYPE_ENVIRONMENT_SANDBOX, // Ambiente a ser enviada a requisição
            '2da392a6-79d2-4304-a8b7-959572c7e44d' // API-Key
        );

        $communication = new CallApi($configuration);
        $communication->setClient($client);

        $response = $communication->download('POST', '/', null, 123456);

        $this->assertSame(400, $response->statusCode);
        $this->assertSame('teste', $response->body->teste);
    }
}

