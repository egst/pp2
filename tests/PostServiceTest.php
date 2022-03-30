<?php declare(strict_types = 1);

namespace IW\Tests\Workshop;

use \Exception;
use \RuntimeException;

#use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

use \PHPUnit\Framework\TestCase;

use \IW\Workshop\Client;
use \IW\Workshop\PostService;

/**
 *  @covers PostService
 *  @psalm-suppress PropertyNotSetInConstructor runTestInSeparateProcess, backupStaticAttributes from TestCase
 */
class PostServiceTest extends TestCase {

    #use MockeryPHPUnitIntegration;

    /** @return list<array{int, array{id: int}}> */
    public function successfulPostCreationData (): array {
        return [
            [201, ['id' => 100]],
            [201, ['id' => 101]],
            [201, ['id' => 102]],
        ];
    }

    /** @return list<array{int, array}> */
    public function failedPostCreationData (): array {
        return [
            [201, []],
            [404, []],
            [500, []],
        ];
    }

    /** 
     *  @dataProvider successfulPostCreationData
     *  @param array{id: int} $body
     *  @throws Exception
     */
    public function testSuccessfulPostCreation (int $code, array $body): void {
        $client = new Client\Mock;
        $client->postResult = [$code, json_encode($body, JSON_THROW_ON_ERROR)];
        $service = new PostService($client);

        $this->assertEquals($body['id'], $service->createPost([]));
    }

    /** 
     *  @dataProvider failedPostCreationData
     *  @throws Exception
     */
    public function testFailedPostCreation (int $code, array $body): void {
        $client = new Client\Mock;
        $client->postResult = [$code, json_encode($body, JSON_THROW_ON_ERROR)];
        $service = new PostService($client);

        $this->expectException(RuntimeException::class);
        $service->createPost([]);
    }

}
