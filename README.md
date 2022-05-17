RestBase Bundle
===============

Symfony Bundle which provides base foundation for REST API applications. Features include:
- Exceptions and errors converter to response
- RESTful decoding of HTTP request body and Accept headers

Installation
------------

### Step 1: Install the bundle

    $ composer require visual-craft/rest-base-bundle

### Step 2: Enable the bundle
If you are not using Flex, you also have to enable the bundle by adding the following line in the app/AppKernel.php:

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
Errors
-----
### Configuration
Using the zone configuration, you can specify part of application where error converting enabled. 

Example:
- php
```php
<?php
// app/config/packages/rest-base.php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $configuration = [
        'zone' => [
            [
                'path' => '^/api/',
                'host' => null,
                'methods' => [],
                'ips' => [],
            ],
        ],
    ];

    $container->extension('visual_craft_rest_base', $configuration);
};
```
- yaml
```yaml
visual_craft_rest_base:
    zone:
        path: '^/api/'
        host: null
        methods: []
        ips: []
```

### Supported exceptions
Supported by default exceptions list:
- AuthenticationException
- HttpExceptionInterface
- InsufficientAuthenticationException
- InvalidRequestBodyFormatException
- InvalidRequestContentTypeException
- InvalidRequestException
- ValidationErrorException

### Enable support security exceptions
- php
```php
<?php
// app/config/packages/security.php

//..
'firewalls' => [
    //..
    'main' => [
        //..
        'entry_point' => 'VisualCraft\RestBaseBundle\Security\AuthenticationEntryPoint',
        //..
    ],
    //..
],
//..
```
- yaml
```yaml
security:
    firewalls:
        main:
            entry_point: 'VisualCraft\RestBaseBundle\Security\AuthenticationEntryPoint'
            //..
```
### Support custom exception
You can create and add your own exceptions and convertors for them.

- create your exception
```php
<?php

declare(strict_types=1);

namespace App\Exceptions;


use Throwable;class CustomException extends \RuntimeException
{
    private string $customField;
    
    public function __construct(string $customField, $message = "",$code = 0,Throwable $previous = null)
    {
        parent::__construct($message,$code,$previous);
        $this->customField = $customField;
    }
    
    public function getCustomField(): string
    {
        return $this->customField;
    }
}
```

- create converter
```php
<?php
//app/src/Problem/ExceptionToProblemConverters/InvalidRequestBodyFormatExceptionConverter.php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

class CustomExceptionConverter implements ExceptionToProblemConverterInterface
{
    public function convert(\Throwable $exception): ?Problem
    {
        if (!$exception instanceof CustomException) {
            return null;
        }

        $result = new Problem(
            'Custom exception title',
            Response::HTTP_BAD_REQUEST,
            'custom_exception_type'
        );
        $result->addDetails('cause', 'custom exception cause');

        return $result;
    }
}
```

- throw exception
```php
<?php
//app/src/Controller

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ThrowInvalidRequestExceptionController extends AbstractController
{
    public function __invoke(Request $request): void
    {
        //..
        throw new CustomException();
        //..
    }
}
```

- response body
```php
[
    'title' => 'Custom exception title', 
    'statusCode' => 400, 
    'type' => 'custom_exception_type', 
    'details' => [
        'cause' => 'custom exception cause',
    ]
]
```

### Request Body Deserializer
Api Body Deserializer contains:
- detect deserialization format
- deserialize using symfony/serializer and handle exceptions
- validate using symfony/validator with violations converting

Example:
```php
<?php
// app/src/Controller/ProcessRequestController.php

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use VisualCraft\RestBaseBundle\Request\RequestBodyDeserializer;

class ProcessRequestController extends AbstractController
{
    private RequestBodyDeserializer $deserializer;

    public function __construct(RequestBodyDeserializer $deserializer)
    {
        $this->deserializer = $deserializer;
    }

    public function __invoke(Request $request): Response
    {
        //..
        $testDto = $this->deserializer->deserialize($request, Dto::class);
        //..
    }
}
```
####Content type configuration
- php
```php
<?php
// app/config/packages/rest-base.php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $configuration = [
        'mimeTypes' => [
            'json' => 'application/json',
            //..
        ],
    ];

    $container->extension('visual_craft_rest_base', $configuration);
};
```
- yaml
```yaml
visual_craft_rest_base:
    mimeTypes:
        json: 'application/json'
        //..
```

### Debug
To enable exception stack trace in error response body needed to change config:
- php
```php
<?php
// app/config/packages/rest-base.php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $configuration = [
        'debug' => true,
    ];

    $container->extension('visual_craft_rest_base', $configuration);
};
```
- yaml
```yaml
visual_craft_rest_base:
    debug: true
```

### Failing Validator
Rest base bundle also contained FailingValidator class which can help you validate your data objects with converting violations :
```php
<?php
// app/src/Controller/ProcessRequestController.php

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use VisualCraft\RestBaseBundle\Request\RequestBodyDeserializer;

class ProcessRequestController extends AbstractController
{
    private FailingValidator $validator;

    public function __construct(
        FailingValidator $validator
    ) {
        $this->validator = $validator;
    }

    public function __invoke(Request $request): Response
    {
        //..
        $this->validator->validate($data);
        //..
    }
}
```

Tests
-----
```sh
$ vendor/bin/simple-phpunit install
$ vendor/bin/phpunit
```

Additional Tools
-----
```sh
$ composer install
$ vendor/bin/psalm
$ vendor/bin/php-cs-fixer fix
```

License
-------

This bundle is released under the MIT license. See the complete license in the file: `LICENSE`
