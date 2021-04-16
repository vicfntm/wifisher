<?php


namespace vicfntm\smsService;

use Illuminate\Support\ServiceProvider;
use vicfntm\smsService\Client\NotifyInterface;
use vicfntm\smsService\Client\SmsClient;
use vicfntm\smsService\Mocks\GuzzleMock;

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
                    return new \GuzzleHttp\Client(
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