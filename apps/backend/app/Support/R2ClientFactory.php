<?php

namespace App\Support;

use Aws\S3\S3Client;

class R2ClientFactory
{
    public static function make(): S3Client
    {
        if (app()->bound(S3Client::class)) {
            return app(S3Client::class);
        }

        $config = [
            'region' => config('pam.r2.region', 'auto'),
            'version' => 'latest',
            'endpoint' => config('pam.r2.endpoint'),
            'credentials' => [
                'key' => config('pam.r2.access_key'),
                'secret' => config('pam.r2.secret_key'),
            ],
            'use_path_style_endpoint' => false, // R2 supports virtual-hosted-style but check specific needs
        ];

        return new S3Client($config);
    }
}
