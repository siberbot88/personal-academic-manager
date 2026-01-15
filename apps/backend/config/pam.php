<?php

return [
    'r2' => [
        'account_id' => env('R2_ACCESS_KEY_ID'), // Usually access key acts as ID in S3 compat, actually Account ID is needed for endpoint construction if not full URL
        'access_key' => env('R2_ACCESS_KEY_ID'),
        'secret_key' => env('R2_SECRET_ACCESS_KEY'),
        'bucket' => env('R2_BUCKET'),
        'backup_bucket' => env('R2_BACKUP_BUCKET'),
        'endpoint' => env('R2_ENDPOINT'),
        'region' => env('R2_REGION', 'auto'),
        'presign_exp_minutes' => env('R2_PRESIGN_EXP_MINUTES', 15),
        'upload_max_mb' => 100,
        'allowed_mimes' => [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // docx
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation', // pptx
            'application/zip',
            'image/jpeg',
            'image/png',
            'image/webp',
        ],
    ],
    'backups' => [
        'retention_days' => 14,
    ],
];
