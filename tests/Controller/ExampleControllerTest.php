<?php

namespace App\Tests\Controller;

use App\Service\ExampleService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExampleControllerTest extends WebTestCase
{
    public function testProcessEndpointExists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/example/process?data=test');

        $this->assertResponseIsSuccessful();
    }

    public function testProcessReturnsJson(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/example/process', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode(['data' => 'test']));

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testProcessWithMockedService(): void
    {
        $mockService = $this->createMock(ExampleService::class);
        $mockService->expects($this->once())
            ->method('processData')
            ->with('test')
            ->willReturn('TEST (processed)');

        $client = static::createClient();
        $client->getContainer()->set(ExampleService::class, $mockService);

        $client->request('POST', '/api/example/process', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode(['data' => 'test']));

        $this->assertResponseIsSuccessful();
    }
}

