<?php

declare(strict_types=1);

return [
    [
        'offset' => [0, 8],
        'fingerprint' => '89 50 4E 47 0D 0A 1A 0A',
        'description' => 'PNG格式',
        'type' => 'png'
    ],
    [
        'offset' => [0, 4],
        'fingerprint' => 'FF D8 FF DB',
        'description' => 'JPG格式',
        'type' => 'jpg'
    ],
    [
        'offset' => [0, 4],
        'fingerprint' => 'FF D8 FF EE',
        'description' => 'JPG格式',
        'type' => 'jpg'
    ],
    [
        'offset' => [0, 12],
        'fingerprint' => 'FF D8 FF E0 00 10 4A 46 49 46 00 01',
        'description' => 'JPG with JFIF',
        'type' => 'jpg'
    ],
    [
        'offset' => [0, 12],
        'fingerprint' => 'FF D8 FF E1 ?? ?? 45 78 69 66 00 00',
        'description' => 'JPG with Exif',
        'type' => 'jpg'
    ],
    [
        'offset' => [0, 6],
        'fingerprint' => '47 49 46 38 37 61',
        'description' => 'GIF87a',
        'type' => 'gif'
    ],
    [
        'offset' => [0, 6],
        'fingerprint' => '47 49 46 38 39 61',
        'description' => 'GIF89a',
        'type' => 'gif'
    ],
    [
        'offset' => [0, 2],
        'fingerprint' => '42 4D',
        'description' => 'BMP file',
        'type' => 'bmp'
    ],
    [
        'offset' => [0, 12],
        'fingerprint' => '52 49 46 46 ?? ?? ?? ?? 57 45 42 50',
        'description' => 'Google WebP image file',
        'type' => 'webp'
    ],
    [
        'offset' => [0, 4],
        'fingerprint' => '00 00 01 00',
        'description' => 'ICON图标',
        'type' => 'ico'
    ],
// 暂时取消对tiff的支持
//    [
//        'offset' => [0, 4],
//        'fingerprint' => '4D 4D 00 2A',
//        'description' => 'tiff with big endian format',
//        'type' => 'tiff'
//    ],
//    [
//        'offset' => [0, 4],
//        'fingerprint' => '49 49 2A 00',
//        'description' => 'tiff with little endian format',
//        'type' => 'tiff'
//    ]
];