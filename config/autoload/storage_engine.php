<?php
declare(strict_types=1);

return [
    'default' => env('STORAGE_ENGINE', 'LocalEngine'),
    'storage' => [
        'LocalEngine' => [
            'root' => BASE_PATH . DIRECTORY_SEPARATOR .'runtime' . DIRECTORY_SEPARATOR . 'localstorage'
        ],
        'QCloudCOSEngine' => [
           'region' => 'ap-guangzhou', // required
           'schema' => 'https',
           'bucket' => 'bucket', // required
           'root' => '/', // required
           'credentials' => [
               'appId' => 123456, // required
               'secretId'  => 'secretId', // required
               'secretKey' => 'secretKey' // required
           ]
        ]
    ]
];