<?php

namespace Grocelivery\Utils\Clients;

use Grocelivery\Utils\Interfaces\JsonResponseInterface;
use Grocelivery\Utils\Interfaces\PropagatesAccessToken;
use Grocelivery\Utils\Responses\JsonResponse;

/**
 * Class MailerClient
 * @package Grocelivery\Utils\Clients
 */
class MailerClient extends RestClient implements PropagatesAccessToken
{
    /**
     * @return string
     */
    public function getHost(): string
    {
        return config('grocelivery.mailer.host');
    }

    /**
     * @param string $template
     * @param string $to
     * @param array $data
     * @return JsonResponseInterface
     */
    public function sendMail(string $template, string $to, array $data): JsonResponseInterface
    {
        $this->setData([
            'to' => $to,
            'template' => $template,
            'data' => $data,
        ]);

        return $this->post("/mail");
    }
}