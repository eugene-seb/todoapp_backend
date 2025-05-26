<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TaskControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }
    public function testNoTaskInDB(): void
    {
        $this->client->request('GET', '/all_tasks');

        $data = json_decode($this->client->getResponse()->getContent(), true,);

        self::assertResponseIsSuccessful();
        self::assertEmpty($data);
    }
}
