<?php

declare(strict_types=1);

use Pimple\Container;

return new class() implements ContainerInterface {

    public function get($id)
    {
        if (!isset($this->services[$id])) {
            throw new \RuntimeException('Service "' . $id . '" does not exist.');
        }

        return $this->services[$id];
    }

    public function has($id): bool
    {
        return isset($this->services[$id]);
    }

    private $services = [
        'sms_client' => function() {
            $client = new SMSClient(getenv('SMS_CLIENT_ID') ?: '', getenv('SMS_CLIENT_SECRET') ?: '');

            $client->setToken('1234567890abcdef');
            $client->setTokenExpiresIn(3600);

            return $client;
        },
    ];
};