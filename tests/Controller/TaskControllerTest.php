<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 *   In order to test this class, execute these commands so that the test database is reinitialized:
    php .\bin\console doctrine:database:drop --env=test --force
    php .\bin\console doctrine:database:create --env=test
    php .\bin\console make:migration
    php .\bin\console doctrine:migrations:migrate --env=test
    php bin/console doctrine:fixtures:load --env=test
    php .\bin\phpunit .\tests\Controller\TaskControllerTest.php
 */
final class TaskControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function test_createTask(): void
    {
        $taskDtoEncoded = json_encode([
            'title' => 'Eugene New Task',
            'description' => 'This is a test task',
            'status' => true,
            'priority' => 'High',
            'ownerId' => 27
        ]);

        $this->client->request(
            method: 'POST',
            uri: '/task/create_task',
            parameters: [],
            files: [],
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $taskDtoEncoded
        );

        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_CREATED);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Task created succesfully.']),
            $this->client->getResponse()->getContent()
        );
    }

    public function test_getAllTasks(): void
    {
        $this->client->request('GET', '/task/all_tasks');

        self::assertJson($this->client->getResponse()->getContent());
        $data = json_decode($this->client->getResponse()->getContent(), true,);

        self::assertResponseIsSuccessful();
        self::assertNotEmpty($data);
    }

    public function test_searchTasks(): void
    {
        $this->client->request(
            method: 'GET',
            uri: '/task/search_tasks',
            parameters: ['key' => 'Eugene'],
            files: [],
            server: ['CONTENT_TYPE' => 'application/json'],
            content: null
        );

        self::assertJson($this->client->getResponse()->getContent());
        $data = json_decode($this->client->getResponse()->getContent(), true,);

        self::assertResponseIsSuccessful();
        self::assertNotEmpty($data);
    }

    public function test_updateTask(): void
    {
        $taskDtoEncoded = json_encode([
            'title' => 'Eugene Update',
            'description' => 'Update: This is a test task',
            'status' => false,
            'priority' => 'High',
            'ownerId' => 27
        ]);

        $this->client->request(
            method: 'PUT',
            uri: '/task/update_task/1215',
            parameters: [],
            files: [],
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $taskDtoEncoded
        );

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Task updated succesfully.', $responseData['message']);
    }

    public function test_deleteTask(): void
    {
        $this->client->request('DELETE', '/task/delete_task/1218');

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Task deleted.', $responseData['message']);
    }
}
