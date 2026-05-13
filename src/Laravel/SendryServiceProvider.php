<?php

declare(strict_types=1);

namespace Sendry\Laravel;

use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;
use Sendry\Sendry;
use Sendry\Symfony\SendryTransport;

final class SendryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/sendry.php',
            'sendry',
        );

        $this->app->singleton(Sendry::class, static function ($app): Sendry {
            $config = $app['config']['sendry'] ?? [];

            $apiKey = $config['api_key'] ?? null;
            if (! is_string($apiKey) || $apiKey === '') {
                throw new \RuntimeException(
                    'Sendry API key missing. Set SENDRY_API_KEY in your .env.',
                );
            }

            return new Sendry($apiKey, [
                'base_url' => $config['base_url'] ?? 'https://api.sendry.online',
                'timeout'  => (float) ($config['timeout'] ?? 30),
                'retries'  => (int)   ($config['retries'] ?? 2),
            ]);
        });

        $this->app->alias(Sendry::class, 'sendry');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [__DIR__ . '/../../config/sendry.php' => $this->app['path.config'] . '/sendry.php'],
                'sendry-config',
            );
        }

        $this->registerMailTransport();
    }

    /**
     * Wire `'transport' => 'sendry'` into Laravel's MailManager so apps can use
     * the Mail facade end-to-end (Mail::to($user)->send(...)) without writing a
     * transport themselves. Laravel 10/11/12 all use Symfony Mailer underneath.
     */
    private function registerMailTransport(): void
    {
        if (! $this->app->bound('mail.manager')) {
            return;
        }

        /** @var MailManager $mail */
        $mail = $this->app->make('mail.manager');

        $mail->extend('sendry', function (): SendryTransport {
            /** @var Sendry $sendry */
            $sendry = $this->app->make(Sendry::class);
            return new SendryTransport($sendry);
        });
    }

    /** @return array<int, string> */
    public function provides(): array
    {
        return [Sendry::class, 'sendry'];
    }
}
