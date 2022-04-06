<?php

$configuration = [
    'secret' => 'F00',
    'test' => true,
];

$container->loadFromExtension('framework', $configuration);
