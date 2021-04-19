<?php


namespace Vicfntm\Wifisher\Client;

use GuzzleHttp\RequestOptions;

final class SmsClient extends Client
{

    public function __construct($client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function send(...$params)
    {
        [$url,] = $this->payloadFactory('send', $this->tagId, $this->defineArgs(func_get_args()));
        $this->response = $this->client->request('GET', $url);

        return $this->response;
    }

    public function addTag(string $activityName): void
    {
        [$url, $payload] = $this->payloadFactory('addTag', $activityName);
        $this->response = $this->client->request('POST', $url, [RequestOptions::JSON => $payload]);
        $content = $this->response->getBody()->getContents();
        $this->tagId = $this->getTagId($content);
    }

    public function sendSms(array $data, $activityId = 'default_activity'): void
    {
        [$url,] = $this->payloadFactory('sendSms', $activityId, $data);
        $this->response = $this->client->request('GET', $url, []);
    }

    private function payloadFactory(string $strategy, ...$params): array
    {
        $basement = $this->config['base_host'];
        $payload = [];
        switch ($strategy) {
            case 'addTag':
                [$activity] = $params;
                $queryParams = http_build_query(['access_key' => $this->config['access_key']]);
                $url = sprintf('%s?%s', $basement . $this->config['set_tag_uri'], $queryParams);
                $payload = ['tag_name' => $activity];
                break;
            case 'sendSms' || 'send':
                [$activity, [$phone, $text]] = $params;
                $queryParams = http_build_query(
                    [
                        'access_key'  => $this->config['access_key'],
                        'sender_name' => $this->config['sender_name'],
                        'number'      => $phone,
                        'text'        => urlencode($text),
                        'tag_id'      => $activity,
                    ]
                );
                $url = sprintf('%s?%s', $basement . $this->config['send_sms_uri'], $queryParams);
                break;
            default:
                [$activity, [$phone, $text]] = $params;
                $queryParams = http_build_query(
                    [
                        'access_key'  => $this->config['access_key'],
                        'sender_name' => $this->config['sender_name'],
                        'number'      => $phone,
                        'text'        => rawurlencode($text),
                        'tag_id'      => $activity,
                    ]
                );
                $url = sprintf('%s?%s', $basement . $this->config['send_sms_uri'], $queryParams);
        }

        return [$url, $payload];
    }
}