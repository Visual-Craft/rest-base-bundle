<?php

$configuration = [
    'zone' => [
        [
            'path' => '^/api/',
        ]
    ]
];

$container->loadFromExtension('visual_craft_rest_base', $configuration);
