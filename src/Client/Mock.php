<?php declare(strict_types = 1);

namespace IW\Workshop\Client;

use \RuntimeException;

use \IW\Workshop\Client\Curl;

class Mock extends Curl {

    /** @var array{int, string} */
    public array $postResult = [500, '{}'];

    /**
     *  @return array{int, string}
     */
    public function post(string $url, string $postBody, array $options = []): array {
        if ($url != 'https://jsonplaceholder.typicode.com/posts')
            throw new RuntimeException('Unexpected argument for a mock method.');
        if ($options != ['content-type' => 'application/json'])
            throw new RuntimeException('Unexpected argument for a mock method.');
        return $this->postResult;
    }

}
