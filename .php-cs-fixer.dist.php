<?php

declare(strict_types=1);

use VisualCraft\PhpCsFixerConfig;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->append([
        __DIR__ . '/.php-cs-fixer.dist.php',
    ])
;

$config = PhpCsFixerConfig\Factory::fromRuleSet(new PhpCsFixerConfig\RuleSet\Php74(), ['method_chaining_indentation' => false]);
$config
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache')
;

return $config;
