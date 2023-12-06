<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'pay' => [
            'driver' => 'daily',
            'path' => storage_path('logs/pay/pay.log'),
            'level' => 'debug',
        ],

        'adminlog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/amdin/admin.log'),
            'level' => 'debug',
        ],

        'withdrawal'=>[
            'driver' => 'daily',
            'path' => storage_path('logs/withdrawal/withdrawal.log'),
            'level' => 'debug',
        ],

        'buy'=>[
            'driver' => 'daily',
            'path' => storage_path('logs/buy/buy.log'),
            'level' => 'debug',
        ],

        'reg'=>[
            'driver' => 'daily',
            'path' => storage_path('logs/reg/reg.log'),
            'level' => 'debug',
        ],

        'lottery'=>[
            'driver' => 'daily',
            'path' => storage_path('logs/lottery/lottery.log'),
            'level' => 'debug',
        ],

        'bind_member_tree'=>[
            'driver' => 'daily',
            'path' => storage_path('logs/bind_member_tree/bind_member_tree.log'),
            'level' => 'debug',
        ],

        'automatic'=>[
            'driver' => 'daily',
            'path' => storage_path('logs/automatic/automatic.log'),
            'level' => 'debug',
        ],

        'pf'=>[
            'driver' => 'daily',
            'path' => storage_path('logs/pf/pf.log'),
            'level' => 'debug',
        ],

        'recharge'=>[
            'driver' => 'daily',
            'path' => storage_path('logs/recharge/recharge.log'),
            'level' => 'debug',
        ],

        'collision_reward'=>[
            'driver' => 'daily',
            'path' => storage_path('logs/collision_reward/cr.log'),
            'level' => 'debug',
        ],
        'collision_fail'=>[
            'driver' => 'daily',
            'path' => storage_path('logs/collision_reward/collision_fail.log'),
            'level' => 'debug',
        ],
    ],

];
