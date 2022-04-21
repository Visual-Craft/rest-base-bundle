<?php

declare(strict_types=1);

$configuration = [
    'zone' => [
        [
            'path' => '^/api/',
        ],
    ],
];

$container->loadFromExtension('visual_craft_rest_base', $configuration);
