<?php

declare(strict_types=1);

namespace Vicfntm\Wifisher\Client;

use Illuminate\Support\Facades\Log;

abstract class Client implements NotifyInterface, ResponseInterface
{

    public $settings;
    protected $numbers = [];
    protected $config;
    protected $client;
    protected $text;
    public $tagId;
    protected $smsText = null;
    protected $response;

    public function __construct()
    {
        $this->config = config('wiFisher');
    }

    public function setTagId(int $id): void
    {
        $this->tagId = $id;
    }

    public function generate(): void
    {
    }

    /**
     * Add number to array of recipients
     *
     * @param null $number
     */
    public function addNumber($number = null): void
    {
        if ($number !== null) {
            $this->numbers[] = $number;
        }
    }

    public function setSms(string $text = ''): void
    {
        $this->text = $text;
    }

    protected function validate(): void
    {
        if ($this->smsText === null) {
            throw new \RuntimeException('Sms text must be set');
        }

        if (empty($this->numbers)) {
            throw new \RuntimeException('Numbers not set');
        }
    }

    protected function getTagId(string $response): int
    {
        try {
            $decoded = json_decode(
                $response,
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return $decoded['data']['tag_id'];
        } catch (\Throwable $exception) {
            throw new \RuntimeException('tag has not set');
        }
    }

    public function getResponse()
    {
        return $this->response;
    }

    protected function defineArgs(array $args): array
    {
        if (empty($args)) {
            return [array_pop($this->numbers), $this->smsText];
        }

        return $args;
    }

    private function log(): void
    {
        $numbers = implode(',', $this->numbers);

        Log::debug("SMS Sent to - {$numbers}, with text - {$this->smsText}");
    }

    abstract public function sendSms(array $data);

}