<?php

declare(strict_types=1);

namespace Vicfntm\Wifisher;

use Illuminate\Support\ServiceProvider;
use Vicfntm\Wifisher\Client\NotifyInterface;
use Vicfntm\Wifisher\Client\SmsClient;
use Vicfntm\Wifisher\Mocks\GuzzleMock;
use GuzzleHttp\Client;

class WiFisherServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->publishes(
            [
                __DIR__ . '/config/smsConfig.php' => config_path('wiFisher.php'),
            ]
        );
    }

    public function register(): void
    {
        app()->bind(NotifyInterface::class, SmsClient::class);

        if (app()->environment('test', 'testing', 'local')) {
            $this->setLocalBindings();
        } else {
            $this->setProdBindings();
        }
    }

    private function setProdBindings(): void
    {
        $this->app->when(SmsClient::class)->needs('$client')
            ->give(
                function () {
                    return new Client(
                        [
                            'curl'   => [CURLOPT_SSL_VERIFYPEER => false],
                            'verify' => false,
                        ]
                    );
                }
            );
    }

    private function setLocalBindings(): void
    {
        $this->app->when(SmsClient::class)->needs('$client')
            ->give(
                function () {
                    return new GuzzleMock();
                }
            );
    }
}