<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Optimizer Settings
    |--------------------------------------------------------------------------
    |
    | Here you can configure the image optimization settings for your application.
    |
    */

    'optimizers' => [
        'jpeg' => [
            'enabled' => true,
            'quality' => 85,
            'progressive' => true,
        ],
        'png' => [
            'enabled' => true,
            'optimization_level' => 2,
        ],
        'webp' => [
            'enabled' => true,
            'quality' => 85,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Optimization
    |--------------------------------------------------------------------------
    |
    | Enable or disable automatic image optimization when images are uploaded.
    |
    */
    'auto_optimize' => env('IMAGE_AUTO_OPTIMIZE', true),

    /*
    |--------------------------------------------------------------------------
    | Backup Original Images
    |--------------------------------------------------------------------------
    |
    | Whether to keep a backup of original images before optimization.
    |
    */
    'backup_originals' => env('IMAGE_BACKUP_ORIGINALS', false),

    /*
    |--------------------------------------------------------------------------
    | Maximum File Size
    |--------------------------------------------------------------------------
    |
    | Maximum file size in bytes to optimize. Files larger than this will be skipped.
    |
    */
    'max_file_size' => 5 * 1024 * 1024, // 5MB

    /*
    |--------------------------------------------------------------------------
    | Supported Mime Types
    |--------------------------------------------------------------------------
    |
    | Mime types that are supported for optimization.
    |
    */
    'supported_mime_types' => [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
    ],
];
