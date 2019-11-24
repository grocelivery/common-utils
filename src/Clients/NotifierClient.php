<?php

namespace Grocelivery\Utils\Clients;

use Grocelivery\Utils\Interfaces\JsonResponseInterface;
use Grocelivery\Utils\Interfaces\PropagatesAccessToken;
use Grocelivery\Utils\Responses\JsonResponse;

/**
 * Class NotifierClient
 * @package Grocelivery\Utils\Clients
 */
class NotifierClient extends RestClient implements PropagatesAccessToken
{
    /**
     * @return string
     */
    public function getHost(): string
    {
        return config('grocelivery.notifier.host');
    }

    /**
     * @param string $mailable
     * @param string $to
     * @param array $data
     * @return JsonResponseInterface
     */
    public function sendMail(string $mailable, string $to, array $data): JsonResponseInterface
    {
        $this->setData([
            'to' => $to,
            'data' => $data,
        ]);

        return $this->post("/mail/$mailable");
    }
}