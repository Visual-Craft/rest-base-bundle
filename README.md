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
### Step 3: Security configuration
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
Usage
-----

### Deserializer
```php
<?php
// app/src/Controller/ProcessRequestController.php

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use VisualCraft\RestBaseBundle\Request\RequestBodyDeserializer;

class ProcessRequestController extends AbstractController
{
    /**
     * @var RequestBodyDeserializer
     */
    private $deserializer;

    public function __construct(RequestBodyDeserializer $deserializer)
    {
        $this->deserializer = $deserializer;
    }

    public function __invoke(Request $request): Response
    {
        //..
        $testDto = $this->deserializer->deserialize($request, Dto::class);
        //..
        return new Response('');
    }
}
```

Request requirements:

method: POST

content type: 'application/json' or another configured type

###Content type configuration

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
### Problems converting
Any exceptions can be converted to object for return to easier debugging. 
You can create and add your own convertors.

Example:
```php
<?php
//app/src/Problem/ExceptionToProblemConverters/InvalidRequestBodyFormatExceptionConverter.php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use VisualCraft\RestBaseBundle\Exceptions\InvalidRequestBodyFormatException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

class InvalidRequestBodyFormatExceptionConverter implements ExceptionToProblemConverterInterface
{
    public function convert(\Throwable $exception): ?Problem
    {
        if (!$exception instanceof InvalidRequestBodyFormatException) {
            return null;
        }

        $result = new Problem(
            'Invalid request body format',
            Response::HTTP_BAD_REQUEST,
            'invalid_request_body_format'
        );
        $cause = 'invalid_format';

        if (($previousException = $exception->getPrevious()) !== null) {
            if ($previousException instanceof UnexpectedValueException) {
                $cause = 'unexpected_value';
            } elseif ($previousException instanceof ExtraAttributesException) {
                $cause = 'extra_attributes';
            }
        }

        $result->addDetails('cause', $cause);

        return $result;
    }
}
```
### Failing Validator
Rest base bundle also contained FailingValidator class which can help you validate your data objects:
```php
<?php

//..

class dataManagerClass
{
    private FailingValidator $validator;

    public function __construct(
        FailingValidator $validator
    ) {
        $this->validator = $validator;
    }

    public function validateData(Dto $data)
    {
        $this->validator->validate($data);

        return $data;
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
