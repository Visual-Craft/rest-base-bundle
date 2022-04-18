RestBase Bundle
===============

Symfony Bundle which provides base foundation for REST API applications


Installation
------------

### Step 1: Install the bundle

    $ composer require visual-craft/rest-base-bundle

### Step 2: Enable the bundle
```php
<?php
// app/AppKernel.php

// ...
use VisualCraft\RestBaseBundle\VisualCraftRestBaseBundle;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            // ...
            new VisualCraftRestBaseBundle(),
            // ...
        ];
    }
}
```

Tests
-----
```sh
$ composer install
$ vendor/bin/phpunit
```

License
-------

This bundle is released under the MIT license. See the complete license in the file: `LICENSE`
