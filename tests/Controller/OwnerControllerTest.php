<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


    // Another entity connnected to Owner (User), Tests + use Faker for the data ---Security

/**
 *   In order to test this class, execute these commands so that the test database is reinitialized:
    php .\bin\console doctrine:database:drop --env=test --force
    php .\bin\console doctrine:database:create --env=test
    php .\bin\console make:migration
    php .\bin\console doctrine:migrations:migrate --env=test
    php bin/console doctrine:fixtures:load --env=test
    php .\bin\phpunit .\tests\Controller\OwnerControllerTest.php
 */
final class OwnerControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function test_createOwner(): void
    {
        $ownerDtoEncoded = json_encode([
            'username' => 'Eugene',
            'password' => 'fhjzkvvjzejk@'
        ]);

        $this->client->request(
            method: 'POST',
            uri: '/owner/create_owner',
            parameters: [],
            files: [],
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $ownerDtoEncoded
        );

        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_CREATED);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Owner created succesfully.']),
            $this->client->getResponse()->getContent()
        );
    }

    public function test_getAllOwners(): void
    {
        $this->client->request('GET', '/owner/all_owners');

        self::assertJson($this->client->getResponse()->getContent());
        $data = json_decode($this->client->getResponse()->getContent(), true,);

        self::assertResponseIsSuccessful();
        self::assertNotEmpty($data);
    }

    public function test_updateOwner(): void
    {
        $ownerDtoEncoded = json_encode([
            'username' => 'ETOUNDI',
            'password' => 'fhjzkvvjzejk@'
        ]);

        $this->client->request(
            method: 'PUT',
            uri: '/owner/update_owner/27',
            parameters: [],
            files: [],
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $ownerDtoEncoded
        );

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Owner updated succesfully.', $responseData['message']);
    }

    public function test_deleteOwner(): void
    {
        $this->client->request('DELETE', '/owner/delete_owner/1');

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Owner deleted.', $responseData['message']);
    }
}
