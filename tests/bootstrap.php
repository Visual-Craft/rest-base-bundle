<?php

declare(strict_types=1);

use VisualCraft\RestBaseBundle\Tests\TestApplication\Kernel;

$file = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies using Composer to run the test suite.');
}

require $file;

$kernel = new Kernel();

// delete the existing cache directory to avoid issues
(new Symfony\Component\Filesystem\Filesystem())->remove($kernel->getCacheDir());
