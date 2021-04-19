<?php


namespace Vicfntm\Wifisher\Mocks;


class GuzzleMock
{

    private const RES = '{"status":200,"success":true,"data":{"tag_id":186,"tag_name":"default_activity_5"}}';

    public function request($method, $url, $payload): self
    {
        return $this;
    }

    public function getBody(): self
    {
        return $this;
    }

    public function getContents(): string
    {
        return self::RES;
    }
}