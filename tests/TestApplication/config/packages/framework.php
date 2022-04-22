<?php

declare(strict_types=1);

$configuration = [
    'secret' => 'F00',
    'test' => true,
];

$container->loadFromExtension('framework', $configuration);
