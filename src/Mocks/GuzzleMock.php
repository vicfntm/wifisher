<?php

declare(strict_types=1);

namespace Vicfntm\Wifisher\Mocks;


class GuzzleMock
{

    private const RES = '{"status":200,"success":true,"data":{"tag_id":186,"tag_name":"default_activity_5"}}';

    public function request($method, $url, $payload): self
    {
        return $this;
    }

    public function getBody(): string
    {
        return self::RES;
    }

    public function getContents(): string
    {
        return self::RES;
    }
    public function GetResponse() : self
    {
        return $this;
    }
}